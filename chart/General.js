Ext.require('Ext.chart.*');
Ext.require('Money.chart.*');
Ext.require(['Ext.layout.container.Fit', 'Ext.window.MessageBox']);

Ext.onReady(function () {
    //storeNegatives.loadData(generateData(6, 20));

    window.newStore = Ext.create('Money.chart.store', { });
    window.newStoreInOut = Ext.create('Money.chart.storeinout', { });
    window.newStoreCategory = Ext.create('Money.chart.storecategory', { });
    window.newStore.load();
    window.newStoreInOut.load();
    window.newStoreCategory.load();
    
    function createChartPie()
    {
        var chartPie = Ext.create('Ext.chart.Chart', {
            xtype: 'chart',
            region: 'center',
             style: 'background:#fff',
            animate: true,            
			store: newStore,
            shadow: true,
            legend: {
                position: 'right'
            },
            insetPadding: 60,
            theme: 'Base:gradients',
            series: [{
                type: 'pie',
                field: 'data1',
                showInLegend: true,
                donut: 35,
                tips: {
                  trackMouse: true,
                  width: 140,
                  height: 28,
                  renderer: function(storeItem, item) {
                    //calculate percentage.
                    var total = 0;
                    newStore.each(function(rec) {
                        total += rec.get('data1');
                    });
                    this.setTitle(storeItem.get('name') + ': ' + Math.round(storeItem.get('data1') / total * 100) + '%');
                  }
                },
                highlight: {
                  segment: {
                    margin: 20
                  }
                },
                label: {
                    field: 'name',
                    display: 'rotate',
                    contrast: true,
                    font: '18px Arial'
                }
            }]
        });
        
        return chartPie;
    }

    function createBarRenderer()
    {

        var barRenderer = Ext.create('Ext.chart.Chart', {
            xtype: 'chart',
            region: 'center',
            animate: true,
            style: 'background:#fff',
            shadow: false,
            store: newStore,
            axes: [{
                type: 'Numeric',
                position: 'bottom',
                fields: ['data1'],
                label: {
                    renderer: Ext.util.Format.numberRenderer('0,0')
                   
                },
                title: 'Importo',
                minimum: 0
            }, {
                type: 'Category',
                position: 'left',
                fields: ['name'],
                title: 'Categoria'
            }],
            series: [{
                type: 'bar',
                axis: 'bottom',
                label: {
                    display: 'insideEnd',
                    field: 'data1',
                    renderer: Ext.util.Format.numberRenderer('0 Eur'),
                    //renderer: function(storeItem, item) { return storeItem + "€"; },                                      
                    orientation: 'horizontal',
                    color: '#333',
                    'text-anchor': 'middle',
                    contrast: true
                },
                xField: 'name',
                yField: ['data1'],
                //color renderer
                renderer: function(sprite, record, attr, index, store) {
                    //var fieldValue = Math.random() * 20 + 10;
                    var value = (record.get('data1') >> 0) % 5;
                    var color = ['rgb(213, 70, 121)', 
                                 'rgb(44, 153, 201)', 
                                 'rgb(146, 6, 157)', 
                                 'rgb(49, 149, 0)', 
                                 'rgb(249, 153, 0)'][value];
                           //this.setTitle('cc');
                    return Ext.apply(attr, {
                        fill: color
                    });
                }
                    
                   
            }]
        });
        
        return barRenderer;
    }
    
    function createInOutChart()
    {
        var chart = Ext.create('Ext.chart.Chart', {
            xtype: 'chart',
            region: 'center',
            style: 'background:#fff',
            animate: true,
            shadow: true,
            store: newStoreInOut,
            legend: {
              position: 'right'  
            },
            axes: [{
                type: 'Numeric',
                position: 'bottom',
                fields: ['Entrate', 'Uscite'],
                minimum: 0,
                label: {
                    renderer: Ext.util.Format.numberRenderer('0,0')
                },
                grid: true,
                title: 'Importo'
            }, {
                type: 'Category',
                position: 'left',
                fields: ['name'],
                title: 'Mese/Anno'
            }],
            series: [{
                type: 'bar',
                axis: 'bottom',
                label: {
                    display: 'insideEnd',
                    field: ['Entrate', 'Uscite'],
                    renderer: Ext.util.Format.numberRenderer('0 Eur'),
                    //renderer: function(storeItem, item) { return storeItem + "€"; },                                      
                    orientation: 'horizontal',
                    color: '#333',
                    'text-anchor': 'middle',
                    contrast: true
                },                
                xField: 'name',
                yField: ['Entrate', 'Uscite']
            }]
        });  
        
        return chart;      
    }
    
    function createTips()
    {
        
        var pieModel = [
            {
                name: 'data1',
                data: 10
            },
            {
                name: 'data2',
                data: 10
            },
            {
                name: 'data3',
                data: 10
            },
            {
                name: 'data4',
                data: 10
            },
            {
                name: 'data5',
                data: 10
            }
        ];
        
        var pieStore = Ext.create('Ext.data.JsonStore', {
            fields: ['name', 'data'],
            data: pieModel
        });
        
        var pieChart = Ext.create('Ext.chart.Chart', {
            width: 100,
            height: 100,
            animate: false,
            store: pieStore,
            shadow: false,
            insetPadding: 0,
            theme: 'Base:gradients',
            series: [{
                type: 'pie',
                field: 'data',
                showInLegend: false,
                label: {
                    field: 'name',
                    display: 'rotate',
                    contrast: true,
                    font: '9px Arial'
                }
            }]
        });
        
        var gridStore = Ext.create('Ext.data.JsonStore', {
            fields: ['name', 'data'],
            data: pieModel
        });
        
        var grid = Ext.create('Ext.grid.Panel', {
            store: gridStore,
            height: 130,
            width: 480,
            columns: [
                {
                    text   : 'name',
                    dataIndex: 'name'
                },
                {
                    text   : 'data',
                    dataIndex: 'data'
                }
            ]
        });        
        var tip = {
                    trackMouse: true,
                    width: 580,
                    height: 170,
                    layout: 'fit',
                    items: {
                        xtype: 'container',
                        layout: 'hbox',
                        items: [pieChart, grid]
                    },
                    renderer: function(klass, item) {
                        var storeItem = item.storeItem,
                            data = [{
                                name: 'data1',
                                data: storeItem.get('data1')
                            }, {
                                name: 'data2',
                                data: storeItem.get('data2')
                            }, {
                                name: 'data3',
                                data: storeItem.get('data3')
                            }, {
                                name: 'data4',
                                data: storeItem.get('data4')
                            }, {
                                name: 'data5',
                                data: storeItem.get('data5')
                            }], i, l, html;
                        
                        this.setTitle("Information for " + storeItem.get('name'));
                        pieStore.loadData(data);
                        gridStore.loadData(data);
                        grid.setSize(480, 130);
                    }
                };
        return tip;        
    }
    
    function createDetails(anno, mese, causale)
    {
        //var tp = createTips();
        var tp = createGrid(anno, mese, causale);
        Ext.create('Ext.window.Window', {
            title: 'Dettagli',
            height: 700,
            width: 1000,    
            layout: 'fit',
            items:[tp]
        }).show();
    }

    function render_date(val) {
        debugger
        val = Ext.util.Format.date(val, 'm/d/Y');
        return val;
    }
    
    function createGrid(anno, mese, causale)
    {
        //alert(causale);
        var store = Ext.create('Money.chart.storedetails', 
            { 
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
                        anno: anno,
                        mese: mese,
                        causale: causale
                    },
                    
                }
            });
            
        //store.load();
        //console.log(store);
        //console.log('creo lo store');
        // create the grid
        var grid = Ext.create('Ext.grid.Panel', {
            store: store,
            columns: [
                {id:'ID',header: 'ID', width: 45, sortable: true, dataIndex: 'ID'},
                {header: 'Conto', width: 140, sortable: true, dataIndex: 'Conto'},
                {header: 'Data', width: 100, sortable: true, dataIndex: 'DataMovimento', renderer: render_date},
                //{header: 'Tipo Movimento', width: 140, sortable: true, dataIndex: 'Pagamento'},
                {header: 'Causale', width: 140, sortable: true, dataIndex: 'Causale'},
                {header: 'Importo',align:'right',  width: 100, sortable: true, dataIndex: 'Importo', xtype: 'numbercolumn', format:'0.00'},
                {header: 'Descrizione', flex:1, minWidth:140, sortable: true, dataIndex: 'Descrizione'},
                {header: '<->', width: 35, sortable: true, dataIndex: 'Transazione'},
            ],
            stripeRows: true,
            //height:600,
            //width:2000,
            //renderTo: 'grid-example',
            //title:'test list'
        });  
        //console.log('restituisco la grid');
        return grid;      
    }
    
    function createSerie(name, newStoreCategory)
    {
        var x = 
        { 
            type: 'line', 
            highlight: { size: 7, radius: 7 }, 
            axis: 'left', 
            xField: 'name', 
            yField: name,             
            markerConfig: 
            { 
                type: 'circle', 
                size: 4, 
                radius: 4, 
                'stroke-width': 0 
            }, 
            smooth: true, 
            //tips: createTips(),
            fill: false,
            listeners: {
                'itemclick': function(o) {
                        createDetails(o.storeItem.data.anno, o.storeItem.data.mese, o.series.yField);
			            //var rec = newStoreCategory.getAt(o.index);
                        //console.log(o);
			             //Ext.example.msg('Testing', 'You selected {0}.', rec.get('name'));
                        //Ext.Msg.show({
                        //    title:'Save Changes?',
                        //    msg: 'Name : ' + o.storeItem.data.name + ' ' + o.series.yField,
                        //    buttons: Ext.Msg.OK
                        //});		
                }
            }
         };  
         
         return x;      
    }
    
    function createPanelFilters()
    {
        var panel = Ext.create('Ext.form.Panel', {
            renderTo: Ext.getBody(),
            //title: 'Form Panel',
            region: 'north',
            height: 70,
            bodyStyle: 'padding:5px 5px 0',
            //width: 600,
            //flex: 1,
            border: false,
            fieldDefaults: {
                labelAlign: 'left',
                msgTarget: 'side'
            },
            defaults: {
                border: false,
                xtype: 'panel',
                flex: 1,
                layout: 'anchor'
            },
    
            layout: 'hbox',
            items: [{
                items: [{
                    xtype:'textfield',
                    fieldLabel: 'First Name',
                    anchor: '-5',
                    name: 'first'
                }, {
                    xtype:'textfield',
                    fieldLabel: 'Company',
                    anchor: '-5',
                    name: 'company'
                }]
            }, {
                items: [{
                    xtype:'textfield',
                    fieldLabel: 'Last Name',
                    anchor: '100%',
                    name: 'last'
                },{
                    xtype:'textfield',
                    fieldLabel: 'Email',
                    anchor: '100%',
                    name: 'email',
                    vtype:'email'
                }]
            }],
        });   
        
        return panel;     
    }
    
    function createLineChart()
    {
        var chart = Ext.create('Ext.chart.Chart', {
            xtype: 'chart',
            region: 'center',
            style: 'background:#fff',
            animate: true,
            store: newStoreCategory,
            shadow: true,
            theme: 'Category1',
            legend: {
                position: 'right'
            },
            axes: [{
                type: 'Numeric',
                minimum: 0,
                position: 'left',
                fields: ['ImportoAuto', 'ImportoCasa', 'ImportoAlimenti','ImportoSpeseMediche','ImportoViaggio','ImportoTasse','ImportoTelefonia','ImportoVestiti','ImportoAnimali','ImportoGiardinaggio','ImportoTelevisione','ImportoAltro','ImportoRateizzazioni','ImportoSvago','ImportoIntrattenimento','ImportoOggettiPersonali','ImportoUtilita','ImportoIstruzione'],
                title: 'Importo',
                minorTickSteps: 1,
                grid: {
                    odd: {
                        opacity: 1,
                        fill: '#ddd',
                        stroke: '#bbb',
                        'stroke-width': 0.5
                    }
                }
            }, {
                type: 'Category',
                position: 'bottom',
                fields: ['name'],
                title: 'Mese/Anno'
            }],
            series: [
                createSerie('ImportoAuto', newStoreCategory),
                createSerie('ImportoCasa'),
                createSerie('ImportoAlimenti'),
                createSerie('ImportoSpeseMediche'),
                createSerie('ImportoViaggio'),
                createSerie('ImportoTasse'),
                createSerie('ImportoTelefonia'),
                createSerie('ImportoVestiti'),
                createSerie('ImportoAnimali'),
                createSerie('ImportoGiardinaggio'),
                createSerie('ImportoTelevisione'),
                createSerie('ImportoAltro'),
                createSerie('ImportoRateizzazioni'),
                createSerie('ImportoSvago'),
                createSerie('ImportoIntrattenimento'),
                createSerie('ImportoOggettiPersonali'),
                createSerie('ImportoUtilita'),
                createSerie('ImportoIstruzione'),
                
           ]
        });
        
        return chart;        
    }

    var lastChartUsed = createChartPie();
    var formFilters = createPanelFilters();
    
	window.panel1 = Ext.create('widget.panel', 
	{
        title: 'Statistiche',
        layout: 'border',
        border: false,
        tbar: [
		{            
            text: 'Save Chart',
            handler: function() {
                Ext.MessageBox.confirm('Confirm Download', 'Would you like to download the chart as an image?', function(choice){
                    if (choice == 'yes') { lastChartUsed.save( { type: 'image/png' } ); }
                });
            }
        // }, {
        //     enableToggle: true,
        //     pressed: false,
        //     text: 'Donut',
        //     toggleHandler: function(btn, pressed) {
        //         lastChartUsed.series.first().donut = pressed ? 35 : false;
        //         lastChartUsed.refresh();
        //     }
        }, {
            text: 'Chart Pie',
            //scale: 'large',
            handler: function() {
                // Put it in the exact same place as the old one, that will trigger
                // a refresh of the layout and a render of the chart
                window.panel1.setTitle('Statistiche Chart Pie');
                var oldChart = window.panel1.down('chart'),
                    oldIndex = window.panel1.items.indexOf(oldChart);
                window.panel1.remove(oldChart);
                lastChartUsed = createChartPie();
                window.panel1.insert(oldIndex, lastChartUsed);                
            }   
        }, {
            text: 'Chart Bar',
            //scale: 'medium',
            handler: function() {
                window.panel1.setTitle('Statistiche Chart Bar');
                var oldChart = window.panel1.down('chart'),
                    oldIndex = window.panel1.items.indexOf(oldChart);
                window.panel1.remove(oldChart);
                lastChartUsed = createBarRenderer();
                window.panel1.insert(oldIndex,  lastChartUsed); 
            }    
        }, {
            text: 'Chart Line',
            handler: function() {
                window.panel1.setTitle('Statistiche Chart Line');
                var oldChart = window.panel1.down('chart'),
                    oldIndex = window.panel1.items.indexOf(oldChart);
                window.panel1.remove(oldChart);
                lastChartUsed = createLineChart();
                window.panel1.insert(oldIndex,  lastChartUsed); 
            }  
        }, {
            text: 'Month In/Out',
            handler: function() {
                window.panel1.setTitle('Statistiche Entrate/Uscite');
                var oldChart = window.panel1.down('chart'),
                    oldIndex = window.panel1.items.indexOf(oldChart);
                window.panel1.remove(oldChart);
                lastChartUsed = createInOutChart();
                window.panel1.insert(oldIndex,  lastChartUsed); 
            }                                     
         }, {
             text: 'Return',
             handler: function() {
                 window.location.href = "../Index.php";
             }            
        }],
        items: [formFilters,lastChartUsed]
    });
        
    var vp = Ext.create('Ext.container.Viewport', {
        renderTo: Ext.getBody(),
        layout: 'fit',        
        items: panel1
    });
    

	
});
