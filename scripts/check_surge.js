#!/usr/bin/env node
'use strict';

const credentialsPath = process.argv[2];
if (!credentialsPath) {
    console.error('[check_surge] Usage: node check_surge.js <path-to-credentials.json>');
    process.exit(1);
}

const admin = require('firebase-admin');

const serviceAccount = require(credentialsPath);

admin.initializeApp({
    credential: admin.credential.cert(serviceAccount),
});

const db = admin.firestore();

const DAYS = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

function padTwo(n) {
    return String(n).padStart(2, '0');
}

function getCurrentTimeStr() {
    const now = new Date();
    return padTwo(now.getHours()) + ':' + padTwo(now.getMinutes());
}

function getCurrentDayStr() {
    return DAYS[new Date().getDay()];
}

function timeInRange(start, end, current) {
    if (!start || !end) return false;
    return current >= start && current <= end;
}

async function run() {
    const now = new Date();
    const nowIso = now.toISOString();

    // --- Check surgeStatus/current ---
    const statusRef = db.collection('surgeStatus').doc('current');
    const statusDoc = await statusRef.get();

    if (statusDoc.exists) {
        const status = statusDoc.data();
        if (status.active && status.scheduledEnd) {
            const scheduledEnd = new Date(status.scheduledEnd);
            if (scheduledEnd < now) {
                console.log('[check_surge] Scheduled end reached, deactivating surge.');
                await statusRef.set({
                    active: false,
                    multiplier: 1.0,
                    scheduledEnd: null,
                    activatedAt: null,
                }, { merge: true });
            } else {
                console.log('[check_surge] Surge active, scheduled end not reached yet: ' + status.scheduledEnd);
            }
        } else if (!status.active) {
            console.log('[check_surge] Surge currently inactive.');
        } else {
            console.log('[check_surge] Surge active (no scheduled end).');
        }
    } else {
        console.log('[check_surge] surgeStatus/current does not exist.');
    }

    // --- Load active surge rules ---
    const rulesSnap = await db.collection('surgeRules')
        .where('active', '==', true)
        .where('deleted', '==', false)
        .get();

    if (rulesSnap.empty) {
        console.log('[check_surge] No active surge rules found.');
        await db.terminate();
        return;
    }

    const currentDay = getCurrentDayStr();
    const currentTime = getCurrentTimeStr();
    console.log('[check_surge] Current day: ' + currentDay + ', time: ' + currentTime);

    let bestMultiplier = null;
    let bestRule = null;

    for (const ruleDoc of rulesSnap.docs) {
        const rule = ruleDoc.data();
        console.log('[check_surge] Evaluating rule: ' + rule.name + ' (type: ' + rule.triggerType + ')');

        if (rule.triggerType === 'schedule') {
            const days = Array.isArray(rule.days) ? rule.days : [];
            const dayMatch = days.length === 0 || days.includes(currentDay);
            const timeMatch = timeInRange(rule.startTime, rule.endTime, currentTime);
            if (dayMatch && timeMatch) {
                console.log('[check_surge] Schedule rule matches: ' + rule.name);
                if (bestMultiplier === null || rule.multiplier > bestMultiplier) {
                    bestMultiplier = rule.multiplier;
                    bestRule = rule;
                }
            } else {
                console.log('[check_surge] Schedule rule does not match: day=' + dayMatch + ' time=' + timeMatch);
            }
        } else if (rule.triggerType === 'auto') {
            // Count active drivers
            const driversSnap = await db.collection('users')
                .where('isActive', '==', true)
                .get();
            const activeCount = driversSnap.size;
            console.log('[check_surge] Active drivers: ' + activeCount + ', rule range: ' + rule.minActiveDrivers + '-' + rule.maxActiveDrivers);
            const inRange = activeCount >= (rule.minActiveDrivers || 0) &&
                (rule.maxActiveDrivers <= 0 || activeCount <= rule.maxActiveDrivers);
            if (inRange) {
                console.log('[check_surge] Auto rule matches: ' + rule.name);
                if (bestMultiplier === null || rule.multiplier > bestMultiplier) {
                    bestMultiplier = rule.multiplier;
                    bestRule = rule;
                }
            } else {
                console.log('[check_surge] Auto rule does not match.');
            }
        }
    }

    if (bestRule) {
        console.log('[check_surge] Activating surge via rule "' + bestRule.name + '" with multiplier ' + bestMultiplier);
        await statusRef.set({
            active: true,
            multiplier: bestMultiplier,
            labelFr: bestRule.labelFr || 'Forte demande',
            label: bestRule.labelFr || 'Forte demande',
            zone: bestRule.zone || 'all',
            activatedAt: nowIso,
            activatedByAdminId: 'cron',
            scheduledEnd: null,
        }, { merge: true });
    } else {
        console.log('[check_surge] No matching rule — surge not changed by cron.');
    }

    await db.terminate();
}

run().catch(function (err) {
    console.error('[check_surge] Error:', err);
    process.exit(1);
});
