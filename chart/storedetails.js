

Ext.define('Money.chart.storedetails', {
    extend: 'Ext.data.Store',
     fields: [
      { name: 'ID', type: 'int'}, 
      { name: 'Conto', type: 'string'},     
      { name: 'DataMovimento', type: 'date', dateFormat: 'Y-m-d H:i:s'},
      { name: 'TipoMovimento', type: 'string'},
      { name: 'Causale', type: 'string'},
      { name: 'Importo', type: 'float'},
      { name: 'Descrizione', type: 'string'},
      { name: 'Transazione', type: 'string'},
      { name: 'Consolidato', type: 'string'},     
     ],    
    autoLoad: true,
    //pageSize: 0, // numero de registros por Grid
    proxy: {
        type: 'ajax',
        api: {
            read: '/money/admin/dataDetails.php',
        },
        reader: {
            type: 'json',
            root: 'data',
            successProperty: 'success'
        },
        extraParams: 
        { 
            anno: 2015,
            mese: 10,
            causale: 'causaleXXX' 
        },
        
    },

});    