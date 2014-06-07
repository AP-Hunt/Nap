define(
    ["jquery",
     "vendor/knockout"],
    function ($, ko) {
        var TodoList = {
            self: this,

            todoLists: ko.observableArray(),
            selectedList: ko.observable(null),

            startup: function(){
                var self = this;
                $.ajax({
                    url: "/api/todo-lists",
                    type: "GET",
                    dataType: "json",
                    success: function(data){
                        self.todoLists(data);
                    }
                })
            },

            changeList: function(list){
                var self = this;
                $.ajax({
                    url: "/api/todo-lists/"+list.id,
                    type: "GET",
                    dataType: "json",
                    success: function(data){
                        self.selectedList(data);
                    }
                })
            }
        };

        return TodoList;
    });