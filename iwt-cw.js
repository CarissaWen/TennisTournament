
//This is Jquery language and when output is ready.
$(document).ready(function(){
    $('#send-query').click(function (){ // 'Send' button clicked.
        //Get the values that the user has inputted.
        var query = {file: $('#file').val(), year: $('#year').val(), 
                      tournament:$('#tournament').val(), winner:$('#winner').val(), 
                      runnerup: $('#runner-up').val(),
                      yearop: $('#year-op').val()}
                      
        $.getJSON('iwt-cw.php', query, function (data) { //Get the data from php file.
            $('#output').html('')
            $('#error').html('')
            if("error" in data){// If there is an error, output the error messages.
                $('#error').append($('<p/>').text("Error: " + data.error))
            }
            else{// if there is no error, the results will be displayed.
                var table = $('<table/>')
                // create the header row
                var tableRow = $('<tr/>')
                tableRow.append($('<th/>').text('Year'))
                tableRow.append($('<th/>').text('Tournament'))
                tableRow.append($('<th/>').text('Winner'))
                tableRow.append($('<th/>').text('Runner-up'))
                table.append(tableRow)
                //loop through each row and data, create a new row in the html table.
                $.each(data, function (i, tournament) { 
                    tableRow = $('<tr/>')
                    tableRow.append($('<td/>').text(tournament.year))
                    tableRow.append($('<td/>').text(tournament.tournament))
                    tableRow.append($('<td/>').text(tournament.winner))
                    tableRow.append($('<td/>').text(tournament['runner-up']))
                    table.append(tableRow)

                })
                $('#output').append(table) // add the table to the html
            }
        })

    })
    $('#clear-output').click(function(){ //clear the  output.
        $('#output').html('') 
        $('#error').html('') 
    })
})