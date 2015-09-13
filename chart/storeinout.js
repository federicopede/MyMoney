    
Ext.define('Money.chart.storeinout', {
    extend: 'Ext.data.Store',
    //model: 'UserApp.model.User',
     fields: [
      { name: 'name'},
      { name: 'Entrate', type: 'float'},
      { name: 'Uscite', type: 'float'},
      { name: 'anno', type: 'int'},
      { name: 'mese', type: 'int'},
     ],    
    autoLoad: true,
    //pageSize: 5, // numero de registros por Grid
    proxy: {
        type: 'ajax',
        api: {
            read: '/money/admin/datainout.php',
            // update: 'data/updateUser.php',
            // create: 'data/createUser.php',
            // destroy: 'data/deleteUser.php'
        },
        reader: {
            type: 'json',
            root: 'data',
            successProperty: 'success'
        },
        // writer: {
        //     type: 'json',
        //     root: 'data',
        //     encode: true
        // }
    }
});    