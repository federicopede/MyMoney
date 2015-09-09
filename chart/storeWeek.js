Ext.define('Money.chart.storeweek', {
    extend: 'Ext.data.Store',
    //model: 'UserApp.model.User',
     fields: [
      { name: 'anno'},
      { name: 'settimana'},
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
      { name: 'ImportoTOTALE', type: 'float'},
     ],    
    autoLoad: true,
    proxy: {
        type: 'ajax',
        api: {
            read: '/money/admin/dataWeek.php',
        },
        reader: {
            type: 'json',
            root: 'data',
            successProperty: 'success'
        },
    }
});    