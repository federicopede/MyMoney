Ext.require('Ext.chart.*');
Ext.require('Money.chart.*');
Ext.require(['Ext.layout.container.Fit', 'Ext.window.MessageBox']);

Ext.onReady(function () {
    //storeNegatives.loadData(generateData(6, 20));

    window.newStore = Ext.create('Money.chart.store', { });
    window.newStoreInOut = Ext.create('Money.chart.storeinout', { });
    window.newStoreCategory = Ext.create('Money.chart.storecategory', { });
    window.newStoreWeek = Ext.create('Money.chart.storeweek', { });
    //window.newStoreInOut.load();
    //window.newStoreCategory.load();
    window.newStoreCausali = Ext.create('Money.chart.storeCausali', {});
    window.newStoreConti = Ext.create('Money.chart.storeConti', { });
    
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
                  width: 160,
                  height: 56,
                  renderer: function(storeItem, item) {
                    //calculate percentage.
                    var total = 0;
                    newStore.each(function(rec) {
                        total += rec.get('data1');
                    });
                    var title = storeItem.get('name') + ': ' + Math.round(storeItem.get('data1') / total * 100) + '%';
                    title += '<br/>Importo : ' + storeItem.get('data1').toFixed(2) + ' &#8364;';
                    this.setTitle(title);
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
                },
                listeners:{
                    itemmousedown : function(obj) {
                        debugger
                        createDetails(
                            null,  // anno
                            null, // mese
                            null, // settimana
                            'Importo' + obj.storeItem.data['name'] // causale
                        );                        
                        //alert(obj.storeItem.data['name'] + ' &' + obj.storeItem.data['data1']);
                    }
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
                    renderer: Ext.util.Format.numberRenderer('0 &#8364;'),
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
                },
                listeners:{
                    itemmousedown : function(obj) {
                        debugger
                        createDetails(
                            null,  // anno
                            null, // mese
                            null, // settimana
                            'Importo' + obj.storeItem.data['name'] // causale
                        );                        
                        //alert(obj.storeItem.data['name'] + ' &' + obj.storeItem.data['data1']);
                    }
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
                    renderer: Ext.util.Format.numberRenderer('0 &#8364;'),
                    //renderer: function(storeItem, item) { return storeItem + "€"; },                                      
                    orientation: 'horizontal',
                    color: '#333',
                    'text-anchor': 'middle',
                    contrast: true
                },                
                xField: 'name',
                yField: ['Entrate', 'Uscite'],
                listeners:{
                    itemmousedown : function(obj) {
                        debugger
                        var Tipo = (obj.yField == 'Uscite') ? -1 : 1;
                        var listaCausali = Ext.getCmp('FiltroCausali').getValue().join(',');
                        createDetails(
                            obj.storeItem.data.anno,  // anno
                            obj.storeItem.data.mese, // mese
                            null, // settimana
                            'Importo' + obj.storeItem.data['name'], // causale
                            Tipo,
                            true, //escludiFiltroDate
                            listaCausali
                        );                        
                        //alert(obj.storeItem.data['name'] + ' &' + obj.storeItem.data['data1']);
                    }
                }                    
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
    
    function createDetails(anno, mese, settimana, causale, Tipo, escludiFiltroDate, listaCausali)
    {
        //var tp = createTips();
        var tp = createGrid(anno, mese, settimana, causale, Tipo, escludiFiltroDate, listaCausali);
        Ext.create('Ext.window.Window', {
            title: 'Dettagli    ' + causale,
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
    
    function createGrid(anno, mese, settimana, causale, Tipo, escludiFiltroDate, listaCausali)
    {
        //alert(causale);
        var filtroDataInizio = document.getElementById("filtroDataInizio");
        var filtroDataFine = document.getElementById("filtroDataFine");
                
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
                        settimana: settimana,
                        causale: causale,
                        dataInizio: escludiFiltroDate ? null : filtroDataInizio.value,
                        dataFine: escludiFiltroDate ? null : filtroDataFine.value,
                        Tipo: ((Tipo == null) ? 0 : Tipo),
                        listaCausali: listaCausali
                    },
                    
                }
            });
            
        // create the grid
        var grid = Ext.create('Ext.grid.Panel', {
            store: store,
           features: [{
                ftype: 'summary'
            }],            
            columns: [
                {id:'ID',header: 'ID', width: 45, sortable: true, dataIndex: 'ID', summaryType: 'count',},
                {header: 'Conto', width: 140, sortable: true, dataIndex: 'Conto'},
                {header: 'Data', width: 100, sortable: true, dataIndex: 'DataMovimento', renderer: render_date},
                //{header: 'Tipo Movimento', width: 140, sortable: true, dataIndex: 'Pagamento'},
                {header: 'Causale', width: 140, sortable: true, dataIndex: 'Causale'},
                {header: 'Importo',align:'right',  width: 100, sortable: true, dataIndex: 'Importo', xtype: 'numbercolumn', format:'0.00', summaryType: 'sum',},
                {header: 'Descrizione', flex:1, minWidth:140, sortable: true, dataIndex: 'Descrizione'},
                {header: '<->', width: 35, sortable: true, dataIndex: 'Transazione'},
            ],
            stripeRows: true,
        });  
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
                itemclick: function(o) {
                        createDetails(
                            o.storeItem.data.anno,  // anno
                            o.storeItem.data.mese, // mese
                            null, // settimana
                            o.series.yField // causale
                        );
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
        
        var filtroDataInizio = document.getElementById("filtroDataInizio");
        var filtroDataFine = document.getElementById("filtroDataFine");
                
        var DateStart = Ext.create('Ext.form.DateField', {
            id: 'FiltroDataInizio',
            fieldLabel: 'Data Inizio',
            format: 'Y-m-d',
            altFormats: 'j|j/n|j/n/y|j/n/Y|j-M|j-M-y|j-M-Y',
            editable: false,
            anchor: '-5',
            value: filtroDataInizio.value
        });            
            
        var DateStop = Ext.create('Ext.form.DateField', {
            id: 'FiltroDataFine',
            fieldLabel: 'Data Fine',
            format: 'Y-m-d',
            altFormats: 'j|j/n|j/n/y|j/n/Y|j-M|j-M-y|j-M-Y',
            editable: false,
            anchor: '-5',
            value: filtroDataFine.value
        }); 
        
        var multiComboCausali = Ext.create('Ext.form.field.ComboBox', {
            id: 'FiltroCausali',
            fieldLabel: 'Causali',
            multiSelect: true,
            displayField: 'descrizione',
            valueField: 'id',
            anchor: '-5',
            editable: false,
            store: newStoreCausali,
            queryMode: 'remote'
        });
        
        var multiComboConti = Ext.create('Ext.form.field.ComboBox', {
            fieldLabel: 'Conti',
            multiSelect: true,
            displayField: 'descrizione',
            valueField: 'id',
            anchor: '-5',
            editable: false,
            store: newStoreConti,
            queryMode: 'remote'
        });
                
        var filtri = document.getElementById("filtri");

        var panel = Ext.create('Ext.form.Panel', {
            renderTo: Ext.getBody(),
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
                    fieldLabel: 'Filtri',
                    anchor: '-5',
                    name: 'first',
                    value: filtri.value,
                }, {
                    id: 'cpny',
                    xtype:'textfield',
                    fieldLabel: 'Company',
                    anchor: '-5',
                    name: 'company'
                } ]
            }, {
                items: [multiComboCausali,multiComboConti]
            }, {
                items: [DateStart,DateStop]
            }],
        });   
        
        return panel;     
    }
    
    function createStackedMonthChart()
    {        
        var chart = Ext.create('Ext.chart.Chart', {
            xtype: 'chart',
            animate: true,
            style: 'background:#fff',
            shadow: false,
            store: newStoreCategory,
            legend: {
                position: 'right'
            },
            axes: [{
                type: 'Numeric',
                position: 'bottom',
                fields: ['ImportoAuto', 'ImportoCasa', 'ImportoAlimenti','ImportoSpeseMediche','ImportoViaggio','ImportoTasse','ImportoTelefonia','ImportoVestiti','ImportoAnimali','ImportoGiardinaggio','ImportoTelevisione','ImportoAltro','ImportoRateizzazioni','ImportoMutuo','ImportoSvago','ImportoIntrattenimento','ImportoOggettiPersonali','ImportoUtilita','ImportoIstruzione'],
                title: false,
                //grid: true,
                label: {
                    renderer: function(v) {
                        return String(v).replace(/000000$/, 'M' + ' &#8364;');
                    }
                },
                roundToDecimal: false
            }, {
                type: 'Category',
                position: 'left',
                fields: ['name'],
                title: false
            }],
            series: [{
                type: 'bar',
                axis: 'bottom',
                gutter: 80,
                xField: 'name',
                yField: ['ImportoAuto', 'ImportoCasa', 'ImportoAlimenti','ImportoSpeseMediche','ImportoViaggio','ImportoTasse','ImportoTelefonia','ImportoVestiti','ImportoAnimali','ImportoGiardinaggio','ImportoTelevisione','ImportoAltro','ImportoRateizzazioni','ImportoMutuo','ImportoSvago','ImportoIntrattenimento','ImportoOggettiPersonali','ImportoUtilita','ImportoIstruzione'],                
                stacked: true,
                tips: {
                    trackMouse: true,
                    width: 200,
                    height: 56,
                    // renderer: function(storeItem, item) {
                    //     this.setTitle(String(item.value[1] / 1000000) + 'M');
                    // }
                    renderer: function(storeItem, item) {
                        //calculate percentage.
                        //debugger
                        var total = 0;
                        var v = storeItem.data[item.yField];
                        // storeItem.data.each(function(rec) {
                        //     debugger
                        //     total += rec.get('data1');
                        // });
                        total = 1000;
                        var title = storeItem.get('name');//+ ': ' + Math.round(v / total * 100) + '%';
                        title += '<br/>' + item.yField + ' <br/> ' + v.toFixed(2) + ' &#8364;';
                        this.setTitle(title);
                    }                    
                },
                listeners:{
                    itemclick : function(obj) {
                        debugger
                        createDetails(
                            null,  // anno
                            null, // mese
                            null, // settimana
                            obj.yField // causale
                        );                        
                        //alert(obj.storeItem.data['name'] + ' &' + obj.storeItem.data['data1']);
                    }
                }                
            }]
        });
                
        // var chart = Ext.create('Ext.chart.Chart', {
        //     xtype: 'chart',
        //     region: 'center',
        //     style: 'background:#fff',
        //     animate: true,
        //     store: newStoreCategory,
        //     shadow: true,
        //     theme: 'Category1',
        //     legend: {
        //         position: 'right'
        //     },
        //     axes: [{
        //         type: 'Numeric',
        //         minimum: 0,
        //         position: 'left',
        //         fields: ['ImportoAuto', 'ImportoCasa', 'ImportoAlimenti','ImportoSpeseMediche','ImportoViaggio','ImportoTasse','ImportoTelefonia','ImportoVestiti','ImportoAnimali','ImportoGiardinaggio','ImportoTelevisione','ImportoAltro','ImportoRateizzazioni','ImportoSvago','ImportoIntrattenimento','ImportoOggettiPersonali','ImportoUtilita','ImportoIstruzione'],
        //         title: 'Importo',
        //         minorTickSteps: 1,
        //         grid: {
        //             odd: {
        //                 opacity: 1,
        //                 fill: '#ddd',
        //                 stroke: '#bbb',
        //                 'stroke-width': 0.5
        //             }
        //         }
        //     }, {
        //         type: 'Category',
        //         position: 'bottom',
        //         fields: ['name'],
        //         title: 'Mese/Anno'
        //     }],
        //     series: [
        //         createSerie('ImportoAuto', newStoreCategory),
        //         createSerie('ImportoCasa'),
        //         createSerie('ImportoAlimenti'),
        //         createSerie('ImportoSpeseMediche'),
        //         createSerie('ImportoViaggio'),
        //         createSerie('ImportoTasse'),
        //         createSerie('ImportoTelefonia'),
        //         createSerie('ImportoVestiti'),
        //         createSerie('ImportoAnimali'),
        //         createSerie('ImportoGiardinaggio'),
        //         createSerie('ImportoTelevisione'),
        //         createSerie('ImportoAltro'),
        //         createSerie('ImportoRateizzazioni'),
        //         createSerie('ImportoSvago'),
        //         createSerie('ImportoIntrattenimento'),
        //         createSerie('ImportoOggettiPersonali'),
        //         createSerie('ImportoUtilita'),
        //         createSerie('ImportoIstruzione'),
        //         
        //    ]
        // });
        
        return chart;        
    }
    

    function createWeekChart()
    {
        var chart = Ext.create('Ext.chart.Chart', {
            xtype: 'chart',
            animate: true,
            style: 'background:#fff',
            shadow: false,
            store: newStoreWeek,
            legend: {
                position: 'right'
            },
            axes: [{
                type: 'Numeric',
                position: 'bottom',
                fields: ['ImportoAuto', 'ImportoCasa', 'ImportoAlimenti','ImportoSpeseMediche','ImportoViaggio','ImportoTasse','ImportoTelefonia','ImportoVestiti','ImportoAnimali','ImportoGiardinaggio','ImportoTelevisione','ImportoAltro','ImportoRateizzazioni','ImportoMutuo','ImportoSvago','ImportoIntrattenimento','ImportoOggettiPersonali','ImportoUtilita','ImportoIstruzione'],
                title: false,
                grid: true,
                label: {
                    renderer: function(v) {
                        return String(v).replace(/000000$/, 'M' + ' &#8364;');
                    }
                },
                roundToDecimal: false
            }, {
                type: 'Category',
                position: 'left',
                fields: ['name'],
                title: false
            }],
            series: [{
                type: 'bar',
                axis: 'bottom',
                gutter: 80,
                xField: 'name',
                yField: ['ImportoAuto', 'ImportoCasa', 'ImportoAlimenti','ImportoSpeseMediche','ImportoViaggio','ImportoTasse','ImportoTelefonia','ImportoVestiti','ImportoAnimali','ImportoGiardinaggio','ImportoTelevisione','ImportoAltro','ImportoRateizzazioni','ImportoMutuo','ImportoSvago','ImportoIntrattenimento','ImportoOggettiPersonali','ImportoUtilita','ImportoIstruzione'],                
                stacked: true,
                tips: {
                    trackMouse: true,
                    width: 200,
                    height: 86,
                    // renderer: function(storeItem, item) {
                    //     this.setTitle(String(item.value[1] / 1000000) + 'M');
                    // }
                    renderer: function(storeItem, item) {
                        //calculate percentage.
                        //debugger
                        var total = storeItem.data["ImportoTOTALE"];
                        var v = storeItem.data[item.yField];
                        // storeItem.data.each(function(rec) {
                        //     debugger
                        //     total += rec.get('data1');
                        // });
                        var title = 'Settimana ' + storeItem.get('name');//+ ': ' + Math.round(v / total * 100) + '%';
                        title += '<br/>' + item.yField + ' <br/> ' + v.toFixed(2) + ' &#8364;';
                        title += ' <br/> TOTALE <br/> ' + total.toFixed(2) + ' &#8364;'
                        this.setTitle(title);
                    }                    
                },
                listeners:{
                    itemclick : function(obj) {
                        debugger
                        createDetails(
                            obj.storeItem.data.anno,  // anno
                            null, // mese
                            obj.storeItem.data.settimana, // settimana
                            obj.yField // causale
                        );                        
                        //alert(obj.storeItem.data['name'] + ' &' + obj.storeItem.data['data1']);
                    }
                }                  
            }]
        });
        
        return chart;        
    }    

    var lastChartUsed = createChartPie();
    var lastStoreUsed = null;
    var formFilters = createPanelFilters();
    
	window.panel1 = Ext.create('widget.panel', 
	{
        title: 'Statistiche',
        layout: 'border',
        border: false,
        tbar: [
		{            
            text: 'Save Chart',
            scale: 'medium',
            handler: function() {
                Ext.MessageBox.confirm('Confirm Download', 'Would you like to download the chart as an image?', function(choice){
                    if (choice == 'yes') { lastChartUsed.save( { type: 'image/png' } ); }
                });
            }
         }, {
             xtype:'tbseparator',
        //     enableToggle: true,
        //     pressed: false,
        //     text: 'Donut',
        //     toggleHandler: function(btn, pressed) {
        //         lastChartUsed.series.first().donut = pressed ? 35 : false;
        //         lastChartUsed.refresh();
        }, {
            //xtype: 'groupbutton',
            enableToggle: true,
            text: 'Pie',
            toggleGroup: 'mygroup',
            pressed: true,
            scale: 'medium',
            handler: function() {
                // Put it in the exact same place as the old one, that will trigger
                // a refresh of the layout and a render of the chart
                window.panel1.setTitle('Statistiche Chart Pie');
                var oldChart = window.panel1.down('chart'),
                    oldIndex = window.panel1.items.indexOf(oldChart);
                window.panel1.remove(oldChart);
                lastChartUsed = createChartPie();
                window.panel1.insert(oldIndex, lastChartUsed);   
                lastStoreUsed = window.newStore; 
                window.newStore.load({
                    params: {
                        DataInizio: Ext.getCmp('FiltroDataInizio').getSubmitValue(),
                        DataFine: Ext.getCmp('FiltroDataFine').getSubmitValue(),
                        Causali: Ext.getCmp('FiltroCausali').getValue().join(','),
                    },
                    callback: function(records, operation, success) { },
                });                            
            }   
        }, {
            //xtype: 'groupbutton',
            enableToggle: true,
            text: 'Bar',
            toggleGroup: 'mygroup',
            scale: 'medium',
            handler: function() {
                window.panel1.setTitle('Statistiche Chart Bar');
                var oldChart = window.panel1.down('chart'),
                    oldIndex = window.panel1.items.indexOf(oldChart);
                window.panel1.remove(oldChart);
                lastChartUsed = createBarRenderer();
                window.panel1.insert(oldIndex,  lastChartUsed); 
                lastStoreUsed = window.newStore;
                window.newStore.load({
                    params: {
                        DataInizio: Ext.getCmp('FiltroDataInizio').getSubmitValue(),
                        DataFine: Ext.getCmp('FiltroDataFine').getSubmitValue(),
                        Causali: Ext.getCmp('FiltroCausali').getValue().join(','),
                    },
                    callback: function(records, operation, success) { },
                });                 
            }    
        }, {
            enableToggle: true,
            text: 'Stacked Month',
            toggleGroup: 'mygroup',
            scale: 'medium',
            handler: function() {
                window.panel1.setTitle('Statistiche Chart Line');
                var oldChart = window.panel1.down('chart'),
                    oldIndex = window.panel1.items.indexOf(oldChart);
                window.panel1.remove(oldChart);
                lastChartUsed = createStackedMonthChart();
                window.panel1.insert(oldIndex,  lastChartUsed); 
                lastStoreUsed = window.newStoreCategory;
                window.newStoreCategory.load({
                     params: {
                         DataInizio: Ext.getCmp('FiltroDataInizio').getSubmitValue(),
                         DataFine: Ext.getCmp('FiltroDataFine').getSubmitValue(),
                         Causali: Ext.getCmp('FiltroCausali').getValue().join(','),
                     },
                    callback: function(records, operation, success) { },
                });                 
            }  
        }, {
            enableToggle: true,
            text: 'Stacked Week',
            toggleGroup: 'mygroup',
            scale: 'medium',
            handler: function() {
                window.panel1.setTitle('Statistiche Chart Week');
                var oldChart = window.panel1.down('chart'),
                    oldIndex = window.panel1.items.indexOf(oldChart);
                window.panel1.remove(oldChart);
                lastChartUsed = createWeekChart();
                window.panel1.insert(oldIndex,  lastChartUsed); 
                lastStoreUsed = window.newStoreWeek;
                window.newStoreWeek.load({
                     params: {
                         DataInizio: Ext.getCmp('FiltroDataInizio').getSubmitValue(),
                         DataFine: Ext.getCmp('FiltroDataFine').getSubmitValue(),
                         Causali: Ext.getCmp('FiltroCausali').getValue().join(','),
                     },
                    callback: function(records, operation, success) { },
                });                 
            }  
            
            
        }, {
            enableToggle: true,
            text: 'Month In/Out',
            toggleGroup: 'mygroup',
            scale: 'medium',
            handler: function() {
                window.panel1.setTitle('Statistiche Entrate/Uscite');
                var oldChart = window.panel1.down('chart'),
                    oldIndex = window.panel1.items.indexOf(oldChart);
                window.panel1.remove(oldChart);
                lastChartUsed = createInOutChart();
                window.panel1.insert(oldIndex,  lastChartUsed); 
                lastStoreUsed = window.newStoreInOut;
                window.newStoreInOut.load({
                     params: {
                         DataInizio: null, //Ext.getCmp('FiltroDataInizio').getSubmitValue(),
                         DataFine: null, //Ext.getCmp('FiltroDataFine').getSubmitValue(),
                         Causali: Ext.getCmp('FiltroCausali').getValue().join(','),
                     },
                    callback: function(records, operation, success) { },
                });                  
            }    
         }, {
             xtype:'tbseparator',      
         }, {
             text: 'Refresh',
             scale: 'medium',
             handler: function() {
                if (lastStoreUsed != null)
                {
                    lastStoreUsed.load({
                         params: {
                             DataInizio: Ext.getCmp('FiltroDataInizio').getSubmitValue(),
                             DataFine: Ext.getCmp('FiltroDataFine').getSubmitValue(),
                             Causali: Ext.getCmp('FiltroCausali').getValue().join(','),
                         },
                        callback: function(records, operation, success) { },
                    });
                }                                    
             }                                         
         }, {
             text: 'Return',
             scale: 'medium',
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
    
    lastStoreUsed = window.newStore;
    window.newStore.load({
                    params: {
                        DataInizio: Ext.getCmp('FiltroDataInizio').getValue(),
                        DataFine: Ext.getCmp('FiltroDataFine').getValue(),
                        Causali: Ext.getCmp('FiltroCausali').getValue().join(','),
                    },
        callback: function(records, operation, success) { },
    });
    
    	
});
