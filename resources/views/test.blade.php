<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        html, body {
            margin: 0;
            padding: 0;
        }

        body {
            background-color: #f5f0f0;
        }

        .wrapper {
            display: flex;
            flex-direction: row;
            gap: 2rem;
            justify-content: center;
            margin-top: 3rem;
        }

        .sidebar {
            background-color: #9f9f9f;
            color: white;
            padding: 20px;
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .file-wrapper {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            justify-content: center;
            align-items: center;
            margin: 0;
            background-color: #f0f0f0;
            font-family: Arial, sans-serif;
        }

        .paper {
            width: 210mm;
            height: 297mm;
            background: white;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            padding: 20mm;
            box-sizing: border-box;
        }

        .paper-row {
            width: 100%;
            display: flex;
            font-size: 16px;
            min-height: 16px;
        }

        .basic-text {
            background-color: white;
            color: black;
        }

        .basic-input {
            background-color: white;
            color: white;
            border-bottom: 1px solid black;
            margin: 0;
            min-width: 20mm;
            font-size: inherit;
        }

        .selected {
            border: 1px dotted red;
        }
    </style>

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.3/themes/base/jquery-ui.css">

    <script src="{{ asset('js/app.js') }}" defer></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js" defer></script>

</head>
<body>
    <div class="wrapper">
        <div class="sidebar">
            <p class="draggable basic-text mb-0">Text</p>
            <p class="draggable basic-input">Input</p>
        </div>

        <div class="file-wrapper">
            <div class="paper">
                <div class="paper-row align-items-center border"></div>
            </div>
            <div class="paper">

            </div>
        </div>

        <div class="sidebar" id="selection">
            <h4>Selected element</h4>
            <p>Nothing selected</p>

            <div class="info">
                <label for="content">Content</label>
                <input type="text" class="form-control" id="content">
            </div>

        </div>
    </div>

    <script>

        let selectedElement = null;

        function renderSelectedInfo() {
            let selectableInfo = $('#selection');

            selectableInfo.find('#content').val(selectedElement.text() ?? selectedElement.val());
        }

        $(document).ready(function() {

            $(".paper").on('click', '.selectable', function() {
                selectedElement?.removeClass('selected');
                selectedElement = $(this);
                selectedElement.addClass('selected');

                renderSelectedInfo();
            });

            $('#selection').on('keypress', '#content', function() {
                if(!selectedElement) return;

                selectedElement.text($(this).val());
            });

            $(".draggable").draggable({
                revert: "invalid",
                helper: "clone",
                zIndex: 100,
                cursorAt: { left: 5, top: 5 }
            });

            $(".paper-row").droppable({
                accept: ".draggable",
                drop: function (event, ui) {
                    let droppedItem = ui.draggable;
                    let clone = droppedItem
                        .clone()
                        .removeClass(['draggable', 'ui-draggable', 'ui-draggable-handle'])
                        .addClass('selectable');

                    $(this).append(clone);
                }
            });
        });

    </script>

</body>
</html>
