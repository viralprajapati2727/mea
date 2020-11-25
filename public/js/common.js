$(function () {

    if (typeof DT_Search === 'undefined'){ var DT_Search = "Search"; }
    if (typeof DT_Show === 'undefined'){ var DT_Show = "Show"; }
    if (typeof Btn_Next === 'undefined'){ var Btn_Next = "Next"; }
    if (typeof Btn_Previous === 'undefined'){ var Btn_Previous = "Previous"; }
    if (typeof Type_to_search === 'undefined'){ var Type_to_search = "Type to search"; }
    if (typeof DT_Info === 'undefined'){ var DT_Info = "Showing _START_ to _END_ of _TOTAL_ entries"; }
    if (typeof DT_info_none === 'undefined'){ var DT_info_none = "Showing 0 to 0 of 0 entries"; }
    if (typeof DT_info_filter === 'undefined'){ var DT_info_filter = "(filtered from _MAX_ total entries)"; }
    if (typeof DT_No_data_available_in_table === 'undefined'){ var DT_No_data_available_in_table = "No data available in table"; }
    if (typeof DT_No_matching_records_found === 'undefined'){ var DT_No_matching_records_found = "No matching records found"; }
    if (typeof File_Default_Text === 'undefined'){ var File_Default_Text = "No file selected"; }
    if (typeof Choose_File === 'undefined'){ var Choose_File = "Choose File"; }

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

// Table setup
    $.extend($.fn.dataTable.defaults, {
        autoWidth: false,
        responsive: true,
        displayLength: 10,
        dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
        //"ordering": false,
        language: {
            search: '<span>'+DT_Search+':</span> _INPUT_',
            lengthMenu: '<span>'+DT_Show+':</span> _MENU_',
            paginate: {'first': 'First', 'last': 'Last', 'next': Btn_Next, 'previous': Btn_Previous},
            searchPlaceholder: Type_to_search,
            info: DT_Info,
            infoEmpty: DT_info_none,
            infoFiltered: DT_info_filter,
            sEmptyTable:DT_No_data_available_in_table,
            sZeroRecords:DT_No_matching_records_found
        },
        drawCallback: function () {
            $(this).find('tbody tr').slice(-3).find('.dropdown, .btn-group').addClass('dropup');
        },
        preDrawCallback: function () {
            $(this).find('tbody tr').slice(-3).find('.dropdown, .btn-group').removeClass('dropup');
        }
    });
});

$(document).ready(function() {
 
    $("#owl-demo").owlCarousel({ 
   
        navigation : true, // Show next and prev buttons
        slideSpeed : 300,
        paginationSpeed : 400, 
        singleItem:true
   
        // "singleItem:true" is a shortcut for:
        // items : 1, 
        // itemsDesktop : false,
        // itemsDesktopSmall : false,
        // itemsTablet: false,
        // itemsMobile : false
   
    });
   
  });