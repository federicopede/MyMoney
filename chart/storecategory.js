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
    
    
Ext.define('Money.chart.storecategory', {
    extend: 'Ext.data.Store',
    //model: 'UserApp.model.User',
     fields: [
      { name: 'anno'},
      { name: 'mese'},
      { name: 'name'},
      { name: 'ImportoAuto', type: 'float'},
      { name: 'ImportoCasa', type: 'float'},
      { name: 'ImportoAlimenti', type: 'float'},
      
      { name: 'ImportoSpeseMediche', type: 'float'},
      { name: 'ImportoViaggio', type: 'float'},
      { name: 'ImportoTasse', type: 'float'},
      { name: 'ImportoTelefonia', type: 'float'},
      { name: 'ImportoVestiti', type: 'float'},
      { name: 'ImportoAnimali', type: 'float'},
      { name: 'ImportoGiardinaggio', type: 'float'},
      { name: 'ImportoTelevisione', type: 'float'},
      { name: 'ImportoAltro', type: 'float'},
      { name: 'ImportoRateizzazioni', type: 'float'},
      { name: 'ImportoMutuo', type: 'float'},
      { name: 'ImportoSvago', type: 'float'},
      { name: 'ImportoIntrattenimento', type: 'float'},
      { name: 'ImportoOggettiPersonali', type: 'float'},
      { name: 'ImportoUtilita', type: 'float'},
      { name: 'ImportoIstruzione', type: 'float'},
     ],    
    autoLoad: true,
    //pageSize: 5, // numero de registros por Grid
    proxy: {
        type: 'ajax',
        api: {
            read: '/money/admin/dataCategory.php',
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