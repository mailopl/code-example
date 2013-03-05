function suggestCity(val){
  dojo.xhrGet({
      url: "/mvc/elements/ajax/city.php?string=" +val,
      load: function(data){
        dojo.byId('dropdown').innerHTML = data;
      }
  });
}