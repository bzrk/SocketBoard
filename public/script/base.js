
function Client(address)
{
    this.socket = new WebSocket(address);
    this.socket.listener = {}; 
    this.socket.onopen = function(e) {
        console.log("Connection established!");
    };
    
    this.socket.onmessage = function(e) 
    {
        var data = JSON.parse(e.data);
        console.log(data)
        
        if(data.command != undefined && data.data != undefined){
            
            console.log(this.listener[data.command]);
            this.listener[data.command].forEach(function(listener){
                listener.execute(data.data);
            });
        }
    };
    
    this.send = function(command, request)
    {
        this.socket.send(JSON.stringify({
            "command" : command,
            "data" : request,
        }));
    }
    
    this.addListener = function(command, listener)
    {
        if(!Array.isArray(this.socket.listener[command])){
            this.socket.listener[command] = [];
        }
        this.socket.listener[command].push(listener);
    }
}

function AddTaskListener()
{
    this.execute = function(data){ console.log(data);
        taskBoard.setTask(data);
    }
}

function InitTaskListener()
{
    this.execute = function(data){
        if(Array.isArray(data)){
            taskBoard.setTasks(data);
        }
        console.log(taskBoard)
    }
}

var taskBoard = {
    tasks: {},
    setTask : function(task){
        this.tasks[task.id] = task;
        this.taskBoardUi.setTask(task);
    },
    setTasks : function(tasks){
        var self = this;
        tasks.forEach(function(task){
            self.setTask(task);
        });
    },
    taskBoardUi : {
        setTask : function(task){
            var gui = $('[key=\'' + task.id + '\']');
            if(gui.length > 0){
                gui.remove()
            } 
            this.add(this.createTask(task), task.status);
        },
        add : function(element, status){
            $('#' + status).append(element);
        },
        createTask : function(task){
            var element = $('<div class="task" key="' + task.id + '">' + 
                                '<div class="title">' + task.title + '</div>' + 
                                '<div class="content">' + task.status + '</div>' + 
                            '</div>');
            element.draggable({
                start: function( event, ui ) {
                    $(this).css('opacity', 0.6);
                },
                stop: function( event, ui ) {
                    $(this).css('opacity', 1);
                }
            });
            return element;
        }
    }
};


$(document).ready(function(){
    client = new Client('ws://localhost:8080');
    client.addListener('AddTask', new AddTaskListener());
    client.addListener('UpdateTask', new AddTaskListener());
    client.addListener('init', new InitTaskListener());
    
    $('#button').click(function(){
        client.send('AddTask', {
            'title' : $('#text').val(),
            'status' : 'open',
        });
    });
    
    
    $('.states').droppable({
        drop: function( event, ui ) {
            client.send('SetStatus', {
                key : ui.draggable.attr('key'),
                status : $(this).attr('id') ,
            });
        }
    });
});



