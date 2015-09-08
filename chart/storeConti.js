Ext.define('Money.chart.storeConti', {
    extend: 'Ext.data.Store',
     fields: [
      { name: 'id', type: 'int'},
      { name: 'descrizione'}
     ],    
    autoLoad: true,
    proxy: {
        type: 'ajax',
        api: {
            read: '/money/admin/dataConti.php',
        },
        reader: {
            type: 'json',
            root: 'data',
            successProperty: 'success'
        },
    }
});    