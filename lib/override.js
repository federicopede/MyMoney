
Ext.define('Override.form.field.ComboBox', {
	override: 'Ext.form.field.ComboBox',

	initialize: function ()
	{
		this.callOverridden(arguments);
	},
	initComponent: function ()
	{
		this.callOverridden(arguments);

        var picker = this.getPicker();
		//this.layout.overflowHandler.scrollIncrement = 100;
        picker.un('itemcontextmenu');
        picker.on('itemcontextmenu', this.OnContextMenuDropDown);        
	},
	
    onViewReady: function ()
    {
        this.callParent();
debugger

    },

    onClickDropDownSelectAll: function (Element)
    {
        if (Element != null && Element != "")
        {
            if (this.picker && this.picker.dropDownId && $("#" + this.picker.dropDownId).find("#" + Element.id).length > 0)
                return true;
            else
                return this.onClickDropDownSelectAll(Element.parentNode);
        }
        else
            return false;
    },

    collapseIf: function (e)
    {
        var me = this;
        if (this.allowBlur !== true && !me.isDestroyed && !e.within(me.bodyEl, false, true) && !e.within(me.picker.el, false, true) && !this.onClickDropDownSelectAll(e.target))
        {
            me.collapse();
        }
    },

    OnContextMenuDropDown: function (MultiCombo, Record, Item, Index, EventObject)
    {
        EventObject.stopEvent();

        var ID0 = Ext.id();

        this.dropDownId = ID0;

        var menu = new Ext.menu.Menu({
            cls: 'MenuItem',
            id: ID0,
            items:
            [
                {
                    text: "Seleziona Tutto",
                    iconCls: "button-icon-AbilitaTutto",
                    handler: function ()
                    {
                        MultiCombo.selModel.selectAll();
                    }
                },
                {
                    text: "Deseleziona Tutto",
                    iconCls: "button-icon-DisabilitaTutto",
                    handler: function ()
                    {
                        MultiCombo.selModel.deselectAll();
                        MultiCombo.selModel.clearValue();
                    }
                }
            ]
        }).showAt(EventObject.xy);
    },

    getJsonValue: function ()
    {
        // this.callParent() chiama getJsonValue, override definita per l'oggetto Ext.form.field.Base
        return this.callParent().join(',');
    }
});