$(document).ready(function () {

    var mainDiv = $("#dndFiles");
    var legacyMainDiv = $("#regFiles");
    var staticDiv = $("#backText");
    var errorFile = $("#errorFile");
    var srcIconFiles =  ["images/docIcon.png", "images/pdfIcon.png"];
    var successFileFirst = $("#successFile:first"); //TODO: fix horizontal bar above first div item to use this variable
    var loadingBarAmountDiv = $("#fillAmount");
    
    //this function checks for duplicate files; it accepts the current file being read by FileReader
    function duplicateFiles(fileCheck) {

        //keeps track of whether a duplicate was found
        var fileExists = 0;

        //fileName and fileSize of current file being read
        var fileName = fileCheck.name;
        var fileSize = fileCheck.size;

        //grabs all children that are NOT the backtext 'Drop Docs Here'
        mainDiv.children().not(staticDiv).each( function(index) {

            //checks for same value and filename
            if (fileName == $(this).text() && fileSize == $(this).attr("value"))
            {
                fileExists += 1;
            }
        });

        if (fileExists > 0)
        {
            return true;
        }
    }

    //this is the function that takes care of all the file processing
    function docsSelected(myFiles) {

        var counter = 0;
        var numFiles = myFiles.length;

        staticDiv.css("display", "none");

        for (var i = 0, f; f = myFiles[i]; i++) {
            var fileReader = new FileReader();

            fileReader.onload = (function(fileRead) {

                //this is the html for successFiles and errorFiles are written
                var successHtml = ['<div id="successFile" name="' + fileRead.name + '" value="' + fileRead.size + '"><img id="fileType" src="' + srcIconFiles[0] + '"/>' + fileRead.name + '<img id="success" src="images/success.png" /></div>',
                                    '<div id="successFile" name="' + fileRead.name + '" value="' + fileRead.size + '"><img id="fileType" src="' + srcIconFiles[1] + '"/>' + fileRead.name + '<img id="success" src="images/success.png" /></div>'];
                var errorHtml = '<div id="errorFile" value="' + fileRead.size + '">' + fileRead.name + '<img id="error" src="images/error.png" /></div>';

                //every file has a MIME type. the following are for files with file extensions .pdf, .docx & .doc
                //i'm considering adding .ppt, .pptx, .zip  
                if (!fileRead.type.match("application/vnd.openxmlformats-officedocument.wordprocessingml.document") &&
                    !fileRead.type.match("application/pdf") &&
                    !fileRead.type.match("application/msword"))
                {
                    if (!duplicateFiles(fileRead))
                    {
                        mainDiv.append(errorHtml);
                    }

                    //we dont want a line divider on the top for the first item
                    if (mainDiv.children().length == 0)
                    {
                        errorFile.first().css("border-top","none");
                    }
                    numFiles -= 1;
                }

                //if the file is bigger that 5mb
                else if (fileRead.size > 5242880)
                {
                    if (!duplicateFiles(fileRead))
                    {
                        mainDiv.append(errorHtml);
                    }
                    
                    //we dont want a line divider on the top for the first item
                    if (mainDiv.children().length == 0)
                    {
                        errorFile.css("border-top","none");
                    }
                    numFiles -= 1;
                }

                //if all is well return the file as a success
                else
                {
                    return function(e) {

                        //reading the name and value (content) of file
                        var name = escape(fileRead.name);
                        var value = e.target.result;

                        //ajax request sent to uploader.php
                        $.ajax({
                            type: 'POST',
                            url: 'uploader.php',
                            data: {'name': name, 'value': value},
                            async: true,
                            success: function(data) {
                                counter++;

                                //this will check for duplicate files listed (client side) but will upload the file regardless (server side)
                                if (!duplicateFiles(fileRead))
                                {
                                    if (i == 0)
                                    {
                                        loadingBarAmountDiv.css("width", "1%");
                                    }
                                    mainDiv.children().not(staticDiv).css("border-top","1px solid #555");
                                    if (fileRead.type.match("application/vnd.openxmlformats-officedocument.wordprocessingml.document"))
                                    {
                                        mainDiv.prepend(successHtml[0]);
                                    }
                                    else if (fileRead.type.match("application/pdf"))
                                    {
                                        mainDiv.prepend(successHtml[1]);
                                    }
                                    else if (fileRead.type.match("application/msword"))
                                    {
                                        mainDiv.prepend(successHtml[0]);
                                    }
                                }
                                
                                //we dont want a line divider on the top for the first item (which is ALWAYS a successFile)
                                
                                //TODO: find out why $("#successFile:first") cannot be in variable
                                mainDiv.children().first().css("border-top","none");

                                var amount = counter*100/numFiles + "%";

                                if (amount == "100%")
                                {
                                    amount = "99%";
                                }

                                loadingBarAmountDiv.css("width", amount);
                            }
                        });
                    };
                }
            })(f);
            fileReader.readAsDataURL(f);
        }
    }

    mainDiv.on("dragover", function(event) {

        // Do something to UI to make page look droppable
        // Required for drop to work
        return false;
    });

    //if the files are dragged and dropped
    mainDiv.on('drop', function(e) {
        event.stopPropagation();  
        event.preventDefault();

        docsSelected(event.dataTransfer.files);
    });

    //if files are selected using the classic uploader
    legacyMainDiv.on('change', function() {

        docsSelected(event.target.files);
    });

    //centering of content on load
    $(window).on("load", function () {

        //deals with position of main
        var windowWidth = Math.round($(window).width() / 2);
        var divWidth = Math.round($("#main").width() / 2);

        var left = windowWidth - divWidth;

        $("#main").css("margin-left", left);
        $("#container").css("height", $(window).height() - 10);

    });

    //deals with positioning of main when window is resized
    $(window).on("resize", function () {

        windowWidth = Math.round($(window).width() / 2);
        divWidth = Math.round($("#main").width() / 2);

        left = windowWidth - divWidth;

        $("#main").css("margin-left", left);
        $("#container").css("height", $(window).height() - 10);
            
    });

    //hiding and showing the create window for the files
    $("#create").on("click", function() {

        if ($("#create").hasClass("opened"))
        {
            $("#create").removeClass("opened");
            $("#createEvent").slideUp("slow");

            mainDiv.html('<div id="backText">[Drop Docs Here]</div>');

            mainDiv.fadeIn("slow");

            loadingBarAmountDiv.css("width", "1%");
        }
        else
        {
            $("#create").addClass("opened");
            $("#createEvent").slideDown("slow");
        }
    });
});