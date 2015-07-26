    // Ext.define('Money.chart.store', {
    //  extend: 'Ext.data.Store',
    //  fields: [
    //   { name: 'name'},
    //   { name: 'data1', type: 'float'}
    //  ],
    //  remoteFilter: true,
    //  proxy: {
    //    type: 'ajax',
    //    url: '/money/admin/data.php'
    //  }
    // });
    // 
    
    
Ext.define('Money.chart.store', {
    extend: 'Ext.data.Store',
    //model: 'UserApp.model.User',
     fields: [
      { name: 'name'},
      { name: 'data1', type: 'float'}
     ],    
    autoLoad: true,
    //pageSize: 5, // numero de registros por Grid
    proxy: {
        type: 'ajax',
        api: {
            read: '/money/admin/data.php',
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