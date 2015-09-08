Ext.define('Money.chart.storeCausali', {
    extend: 'Ext.data.Store',
     fields: [
      { name: 'id', type: 'int'},
      { name: 'descrizione'}
     ],    
    autoLoad: true,
    proxy: {
        type: 'ajax',
        api: {
            read: '/money/admin/dataCausali.php',
        },
        reader: {
            type: 'json',
            root: 'data',
            successProperty: 'success'
        },
    }
});    