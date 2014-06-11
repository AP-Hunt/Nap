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
                        var getListIndex = function(lists, id){
                            for(var i =0; i <= lists.length-1; i++){
                                var lst = lists[i];
                                if(lst.id == id){
                                    return i;
                                }
                            }
                            return null;
                        };
                        var index = getListIndex(self.todoLists(), data.id);
                        if(index != null){
                            var oldElement = self.todoLists()[index];
                            self.todoLists.replace(oldElement, data);
                            self.selectedList(self.todoLists()[index]);
                        }

                    }
                })
            },

            markComplete: function(listEntry, list){
                var self = this;
                $.ajax({
                    url: "/api/todo-lists/"+list.id+"/entries/"+listEntry.id,
                    type: "PUT",
                    dataType:"json",
                    data: {
                        "complete": true
                    },
                    success: function(data){
                        self._replaceListItem(list.id, listEntry.id, data);
                    }

                })
            },

            _replaceListItem: function(listId, entryID, data)
            {
                var currentData = this.todoLists();

                var getList = function(id){
                    for(var i =0; i <= currentData.length-1; i++){
                        var lst = currentData[i];
                        if(lst.id == id){
                            return lst;
                        }
                    }
                    return null;
                };

                var getListIndex = function(lists, id){
                    for(var i =0; i <= lists.length-1; i++){
                        var lst = lists[i];
                        if(lst.id == id){
                            return i;
                        }
                    }
                    return null;
                };

                var getEntryIndex = function(list, id){
                    for(var i =0; i <= list.items.length-1; i++){
                        var entry = list.items[i];
                        if(entry.id == id){
                            return i;
                        }
                    }
                    return null;
                };



                var lst = getList(listId);
                if(lst != null){
                    var lstIndex = getListIndex(currentData, listId);

                    var entry =  getEntryIndex(lst, entryID);
                    if(entry != null){
                        lst.items[entry] = data;
                    }

                    if(lstIndex != null){
                        var oldElement = this.todoLists()[lstIndex];
                        this.todoLists.replace(oldElement, lst);
                        this.todoLists.valueHasMutated();

                        this.selectedList(this.todoLists()[lstIndex]);
                    }
                }
            }
        };

        return TodoList;
    });