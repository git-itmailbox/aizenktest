/**
 * Created by yura on 19.04.17.
 */
$(document).ready(function () {

    $("#btnSearch").on("click", function () {
        var userId =  $("#user_id").val();
        if(userId !== '' && userId !== undefined)
        return window.location.replace("/main/result/"+userId);
    });

    $(".answer").on("click", function () {
        var answerNum = $("#answerNum").val();
        var answer = ($(this).hasClass('yes'))? 'yes': 'no';
        var userId =  $("#userId").val();
        $.post( "/main/saveanswer",
            {
                answerNum: answerNum,
                answer: answer,
                userId: userId,
            },
            function( data ) {
            if(data.questionNum===null)
                {
                    return window.location.replace("/main/result/"+userId);
                }
            $("#question").text(data.question);
            $("#answerNum").val(data.questionNum);
            $("#questionNum").text(data.questionNum);
        }, "json");
    });
    drawGraph();

    $(function () {
        $("#search").autocomplete({
            source: function (request, response) {
                $.ajax({
                        url: "/searchuser",
                    dataType: "json",
                    data: {
                        search: request.term,
                    },
                    success: function (data) {
                        response(data);
                    },
                    type: "POST",
                });
            },
            minLength: 2,
            delay: 250,
            select: function (event, ui) {
                $("#search").val(ui.item.value);
                $("#user_id").val(ui.item.id);
                return false;
            },
        });
    });

});

function drawGraph() {
    var graph = document.getElementById("graph");
        if(!graph) return;
     var   ctx     = graph.getContext('2d');
    graph.width  = 650;
    graph.height = 650;
    var cell = {
        size:25
    };

    var usrExtraversion = $("#extraversion").val();
    var usrNeuroticism = $("#neuroticism").val();

    var xLvl = graph.height/2+2*cell.size;
    var yLvl = graph.width/2-(2*cell.size);

    ctx.strokeStyle = "#eee";
    ctx.fillStyle = "#aaa";
    ctx.font = "bold 42px sans-serif";
    // ctx.textAlign = "right";
    // ctx.textBaseline = "center";

    ctx.textAlign = "right";
    ctx.fillText("Холерик", graph.width, 50);
    ctx.fillText("Сангвиник", graph.width, graph.height - yLvl/2);

    ctx.textAlign = "left";

    ctx.fillText("Флегматик", 0, graph.height - yLvl/2);
    ctx.fillText("Меланхолик", 5, 50);


    //цвет шрифта снова в черный
    ctx.fillStyle = "#000";

    for (var y = 0.5; y < graph.height; y += cell.size) {
        ctx.moveTo(0, y);
        ctx.lineTo(graph.width, y);
    }
    for (var x = 0.5; x < graph.width; x += cell.size) {
        ctx.moveTo(x, 0);
        ctx.lineTo(x, graph.height);
    }
    ctx.stroke();
    ctx.beginPath();
    //Ось по Y
    ctx.moveTo(yLvl, 0);
    ctx.lineTo(yLvl, graph.height);
    //Ось по X
    ctx.moveTo(0, xLvl);
    ctx.lineTo(graph.width, xLvl);
    ctx.strokeStyle = "#000";
    ctx.stroke();

    //насечки по Х
    for (var x = cell.size; x < graph.width; x += cell.size) {
        ctx.moveTo(x, xLvl-10);
        ctx.lineTo(x, xLvl+10);
    }
    //насечки по Y
    for (var y = cell.size -0.5; y < graph.height; y += cell.size) {
        ctx.moveTo(yLvl-10, y);
        ctx.lineTo(yLvl+10, y);
    }

    ctx.stroke();

    //Значения по Х
    ctx.font = "bold 10px sans-serif";
    for (var x = cell.size; x < graph.width; x += cell.size) {
        if(x/cell.size-1==10 ) continue;
        ctx.fillText(x/cell.size-1, x-3,  xLvl-15);
    }
    //Значения по Y
    for (var y = cell.size; y < graph.height; y += cell.size) {
        if(graph.height/cell.size-y/cell.size-1==10 || graph.height/cell.size - y/cell.size-1==11) continue;
        ctx.fillText(graph.height/cell.size-y/cell.size-1, yLvl+cell.size/2, y+3);
    }

    ctx.font = "bold 12px sans-serif";
    // ctx.textAlign = "right";
    ctx.textBaseline = "top";
    ctx.fillText("Невротизм", 345, 0);

    ctx.textBaseline = "bottom";
    ctx.fillText("Эмоциональная", graph.width/2, graph.height-15);
    ctx.fillText("устойчивость", graph.width/2, graph.height);

    ctx.fillText("Интроверт", 0, xLvl+25);
    ctx.textAlign = "right";
    ctx.fillText("Экстраверт", graph.width, xLvl+25);

    ctx.beginPath();
    ctx.lineWidth = 3;
    ctx.strokeStyle = '#FF0000';
    ctx.arc(usrExtraversion*cell.size+cell.size,
        (graph.height-cell.size) -   (graph.height-cell.size)/cell.size*usrNeuroticism
        , 3, 0, Math.PI*2, true);
    // ctx.fill()
    ctx.stroke();
}
