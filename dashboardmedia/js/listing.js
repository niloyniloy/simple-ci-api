function dosearch(){
	jQuery('.search_text_form').submit();
}

jQuery(document).ready(function($){
  $("#per_page").on("change", function(){
	  $("#per_page_form").submit();
  });

  $('.data_listing').tooltip();

  /*
   * function to make the whole area
   * clickable
   */
  $('#table-listing-items  tr  td').on('click' , function() {

	  var dataClickable = $(this).attr('data-href');

	  if(dataClickable.length > 0){

		  window.location = $(this).attr('data-href');
	  }

  });
  /*
   * function to submit form
   * to sort items
   */
  $('.table-header-for-sort').on('click', function(){
	  $('.table_sort_field').val($(this).attr('data-title'));
	  $('.table_sort_direction').val($(this).attr('data-next'));
	  //alert($('.hidden-form-listing').attr('action'));
	  $('.hidden-form-listing').submit();
	  //alert('hi');
  });

  /*
   * typeahead js for searchbox
   */
  var search_tags = new Bloodhound({
    datumTokenizer: Bloodhound.tokenizers.obj.whitespace('value'),
    queryTokenizer: Bloodhound.tokenizers.whitespace,
    limit: search_auto_suggest_limit,
    prefetch: siteURL +controllerFolder+'/'+controller_name+'/get_search_words/ab',
    remote: siteURL +controllerFolder+'/'+controller_name+'/get_search_words/%QUERY'
  });

  search_tags.initialize();

  $("#searchtext").typeahead({
    hint: true,
    highlight: true,
    minLength: 1
  }, {
    name: 'search',
    displayKey: 'value',
    source: search_tags.ttAdapter()
  }).on('typeahead:selected typeahead:autocompleted', function(e, datum) {
    dosearch();
  });


  $("#searchtext").blur(function(e) {

  });
  $("#searchtext").keypress(function(e) {
	  if (e.keyCode == 13) {
		  dosearch();
		  return false;
	  }
  });


  /*
   * typeahead js ends
   */

  /*
   * jQuery drag and drop for show field start
   */

  $( "ul.droptrue" ).sortable({
      connectWith: "ul",
      dropOnEmpty: true
    });

    $( "ul.dropfalse" ).sortable({
      connectWith: "ul",
      dropOnEmpty: true
    });

    $( "#sortable1, #sortable2, #sortable3" ).disableSelection();

    $( "ul.droptrue_order" ).sortable({
        connectWith: "ul",
        dropOnEmpty: true
      });

      $( "ul.dropfalse_order" ).sortable({
        connectWith: "ul",
        dropOnEmpty: true
      });

      $( "#sortable_order, #sortable_order2, #sortable3" ).disableSelection();

    /*
     * jQuery drag and drop for show field end
     */

});
