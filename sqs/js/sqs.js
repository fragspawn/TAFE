// Globals
var noInQueue = 0;
var queueContents = '';

window.onload = function() {
    getNoInQueue();
    setInterval(getNoInQueue, 30000);
}

function getNoInQueue() { 
    var queueURL = "ws/ws.php?getData=noInQueue";
    $.ajax({
        url: queueURL,
        method: 'get',
        datatype: 'json',
        success: function(res) {
            if(res.noInQueue != noInQueue) {
                getQueue();
                noInQueue = res.noInQueue;
                document.getElementById('noinqueue').innerHTML = res.noInQueue;
            }
        },
        error: function(err) {
            console.log('err');
            console.log(err); 
        }
    });
}

function getQueue() { 
    var queueURL = "ws/ws.php?getData=listqueue";
    $.ajax({
        url: queueURL,
        method: 'get',
        datatype: 'json',
        success: function(res) {
            queueContents = res;
            renderQueue(res);
        },
        error: function(err) {
            console.log('err');
            console.log(err); 
        }
    });
}

function renderQueue(queueArray) {
    outHTML = '';
    for(var loop=0;loop<queueArray.length;loop++) {
        outHTML += '<div class="queuerow">';
        outHTML += '<span>' + queueArray[loop].student_NO + '</span>';
        outHTML += '<span>' + queueArray[loop].queue_TITLE + '</span>';
        outHTML += '<span>' + queueArray[loop].queue_DESC + '</span>';
        outHTML += '<span>' + queueArray[loop].queue_DATE + '</span>';
        outHTML += '<span><a href="#" onClick="deQueue(' + queueArray[loop].queue_ID + ')">dequeue</a></span>';
        outHTML += '</div>';
    }
    document.getElementById('queuelist').innerHTML = outHTML;
}

function enQueue() { 
    var queueURL = "ws/ws.php?getData=enqueue";
    $.ajax({
        url: queueURL,
        method: 'post',
        data: $('#enqueueform').serialize(),
        datatype: 'json',
        success: function(res) {
            if(res.enQueued == 1) {
                getNoInQueue();
                document.getElementById('problem').value = '';
                document.getElementById('description').value = '';
            } else {
                alert('you can\'t do that');
            }
        },
        error: function(err) {
            console.log('err');
            console.log(err); 
        }
    });
}

function deQueue(inVal) {
    var queueURL = "ws/ws.php?getData=dequeue&queueid=" + inVal;
    $.ajax({
        url: queueURL,
        method: 'get',
        datatype: 'json',
        success: function(res) {
            if(res.deQueued == 1) {
                getNoInQueue();
            } else {
                alert('you can\'t do that');
            }
            console.log(res);
        },
        error: function(err) {
            console.log('err');
            console.log(err); 
        }
    });
}
