(function($) {
  'use strict';
  $(function() {
    $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    var todoListItem = $('.todo-list');
    var todoListInput = $('.todo-list-input');
    $('.todo-list-add-btn').on("click", function(event) {
      event.preventDefault();

      var item = $(this).prevAll('.todo-list-input').val();

      $.ajax({
        url: 'flight-tickets/ajax/dashboard-todolist',
        type:'POST',
        data:{
            task : item
        },
        success:function(id){
          if (id) {
            todoListItem.append("<li><div class='form-check'><label class='form-check-label'><input data-id='" +id+ "' class='checkbox' type='checkbox'/>" + item + "<i class='input-helper'></i></label></div><i  data-id='" +id+ "' class='remove mdi mdi-close-circle-outline'></i></li>");
            todoListInput.val("");
          }
        }
      });

      

    });

    todoListItem.on('change', '.checkbox', function() {
      
      if ($(this).attr('checked')) {
        $(this).removeAttr('checked');
        var status = 1;
      } else {
        $(this).attr('checked', 'checked');
        var status = 0;
      }
      var id = $(this).data('id');
      $.ajax({
        url: 'flight-tickets/ajax/dashboard-todolist-update',
        type:'POST',
        data:{
          id : id,
          status : status
        },
        success:function(resp){
        }
      });

      $(this).closest("li").toggleClass('completed');

    });

    todoListItem.on('click', '.remove', function() {
      var id = $(this).data('id');
      $.ajax({
        url: 'flight-tickets/ajax/dashboard-todolist-delete',
        type:'POST',
        data:{
          id : id,
        },
        success:function(resp){
        }
      });
      $(this).parent().remove();
    });

  });
})(jQuery);