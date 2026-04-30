const admin = require(require('path').resolve(__dirname, '../node_modules/firebase-admin'));
const credPath = process.argv[2] || require('path').resolve(__dirname, '../storage/app/firebase/credentials.json');
const sa = require(credPath);

admin.initializeApp({ credential: admin.credential.cert(sa) });
const db = admin.firestore();

async function generatePayouts() {
  const now = new Date();
  const dayOfWeek = now.getDay();
  const lastMonday = new Date(now);
  lastMonday.setDate(now.getDate() - ((dayOfWeek + 6) % 7) - 7);
  lastMonday.setHours(0, 0, 0, 0);
  const lastSunday = new Date(lastMonday);
  lastSunday.setDate(lastMonday.getDate() + 6);
  lastSunday.setHours(23, 59, 59, 999);

  const vendors = await db.collection('vendors').where('weeklyAccrual', '>', 0).get();
  let count = 0;
  const batch = db.batch();

  vendors.forEach(doc => {
    const data = doc.data();
    const amount     = data.weeklyAccrual    || 0;
    const orderCount = data.weeklyOrderCount || 0;
    const name       = data.title || data.name || 'Unknown';
    const payoutRef  = db.collection('weeklyPayouts').doc();
    batch.set(payoutRef, {
      restaurantId:    doc.id,
      restaurantName:  name,
      periodStart:     admin.firestore.Timestamp.fromDate(lastMonday),
      periodEnd:       admin.firestore.Timestamp.fromDate(lastSunday),
      amount:          amount,
      orderCount:      orderCount,
      status:          'pending',
      createdAt:       admin.firestore.FieldValue.serverTimestamp(),
      paidAt:          null,
      paidByAdminId:   null,
      paidByAdminName: null,
      bankRef:         '',
      notes:           '',
    });
    batch.update(db.collection('vendors').doc(doc.id), {
      weeklyAccrual:    0,
      weeklyOrderCount: 0,
    });
    count++;
  });

  if (count > 0) await batch.commit();
  console.log(count + ' payout(s) generated for period ' +
    lastMonday.toISOString().slice(0,10) + ' to ' + lastSunday.toISOString().slice(0,10) + '.');
}

generatePayouts().then(() => process.exit(0)).catch(e => { console.error(e.message); process.exit(1); });
