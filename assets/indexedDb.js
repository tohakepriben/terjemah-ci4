
// Contoh penggunaan
const dbHelper = new IndexedDBHelper('myDatabase', 'myStore');

dbHelper.addData({ name: 'Item 1', value: 'Value 1' }).then((id) => {
    console.log('Data added with ID:', id);
});

dbHelper.getData(1).then((data) => {
    console.log('Data retrieved:', data);
});

dbHelper.updateData({ id: 1, name: 'Updated Item', value: 'Updated Value' }).then(() => {
    console.log('Data updated');
});

dbHelper.deleteData(1).then(() => {
    console.log('Data deleted');
});
