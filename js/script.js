$(document).ready(function(){

    
    $("#searchBtn").on("click", function(e){
        e.preventDefault();

        console.log("order: " + $("#selector").val());
        console.log("searchText: " + $("#searchText").val());
        $.ajax({
            url: "src/api.php",
            type: "GET",
            data: {
                entity: "tracks",
                order: $("#selector").val(),
                searchText: $("#searchText").val()
            },
            succes: function (param) {
                const data = JSON.parse(param)
                console.log(data);
                const holder = $("<div>");
                $.each(data, function (indexInArray, valueOfElement) { 
                    console.log(indexInArray + " " + valueOfElement)
                    holder.append($("<p>").attr("class", "track").text(valueOfElement['Name']));
                });
                
                $("body").append(holder)

            },
            fail: function(param){
                console.log("FAILED")
                console.log(param)
            }
        });
        // .done(function(data){
        //     console.log(data);
        //     console.log($("#selector").val())

        //     console.log($("#searchText").val())
        //     const holder = $("<div>");
        //     $.each(data, function (indexInArray, valueOfElement) { 
        //         console.log(indexInArray + " " + valueOfElement)
        //         // holder.append($("<p>").attr("class", "track").text(valueOfElement['Name']));
        //     });
            
        //     $("body").append(holder)

        // })
        // .fail(function(e){
        //     console.log("Request Failed")
        //     console.log(e)
        // });
            
    });
});