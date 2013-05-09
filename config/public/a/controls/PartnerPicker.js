define([
  "declare", "can", "jquery",

  "models/partner",

  "dijit/form/FilteringSelect",
  "dojo/store/Memory"
], function(declare, can, $,

  Partner,

  FilteringSelect,
  Memory
){

  var partnerStore = new Memory({
    idProperty: 'domain',
    identifier: 'domain'
  });

  // Helper functions for editing panes
  var PartnerPicker = declare("PartnerPicker", FilteringSelect, {
    store: partnerStore,
    queryExpr: '*${0}*',
    autoComplete: false
  });

  PartnerPicker.clear_cache = function() {
    Partner.findAll({}, function(data) {
      partnerStore.setData(data);
    });
  };
  PartnerPicker.clear_cache();

  return PartnerPicker;
});
