(function($) {
    //Toro lineup @Copyright by Intelligence Business (Thailand) Co.,Ltd. intbizth.

    //this array keep %
    var defaultData = [
        {"name": "","x": 50,"y": 92},
        {"name": "","x": 16,"y": 71},
        {"name": "","x": 36,"y": 74},
        {"name": "","x": 63,"y": 74},
        {"name": "","x": 84,"y": 71},
        {"name": "","x": 17,"y": 43},
        {"name": "","x": 35,"y": 50},
        {"name": "","x": 65,"y": 50},
        {"name": "","x": 84,"y": 43},
        {"name": "","x": 35,"y": 23},
        {"name": "","x": 65,"y": 23}
    ];
    $.fn.lineup = function(options) {
        return this.each(function() {
            options.exportHidden = options.exportHidden || true;
            options.warnText = options.warnText || 'Need 11 players.';
            options.playerData = options.playerData || defaultData;


            var $this = $(this),
                $ID = $this.attr('id'),
                $playerList = options.playerList,
                exportHidden = options.exportHidden || true,
                hidden = options.hiddenId || null,
                debug = options.debug || false,
                patternId = options.patternId || null; //ID select ของ pattern

            if ($ID === undefined) {
                $this.html("<h1>Error! Need ID</h1>");
                return;
            }
            if ($playerList.length!= 11) {
                $this.html("<div style='text-align:center'><h3><i class='fa fa-warning'></i>&nbsp;"+options.warnText+"</h3><h5>"+$playerList.length+" / 11</h5></div>");
                return;
            }

            //==== Function zone ====//
            function applySave() {
                logIfDebug(debug, 'drag: applying save..');

                //sorry for use STUPID
                var playerExport = [];
                var saveable = true;
                for (var i = 0; i < 11; i++) {
                    var divData = $('#' + $ID + ' .player' + i + ' li').data('id');
                    playerExport.push({
                        ID: divData,
                        x: options.playerData[i].x,
                        y: options.playerData[i].y
                    });
                    if (divData === undefined)
                        saveable = false;
                }

                //apply data to hidden
                if (debug) { $('#' + $ID + ' .loadVal').val(JSON.stringify(playerExport)); }
                if (hidden!==null) { $('#'+hidden).val(JSON.stringify(playerExport)); }
                return saveable;
            }

            function applyPos() {
                logIfDebug(debug,'drag: applying POS', 'info');
                for (var i = 0; i < 11; i++) {
                    $('#' + $ID + ' .player' + i).css({
                        'left': options.playerData[i].x + '%',
                        'top': options.playerData[i].y + '%'
                    });
                    $('#' + $ID + ' .player' + i + ' .player__name').text(options.playerData[i].name);
                }
            }

            function loadSave() {
                try {
                    if (hidden!==null) {
                        options.playerData = $.parseJSON($('#'+hidden).val());
                    }

                    logIfDebug(debug,'drag: Loaded save');
                    applyPos();
                    for (var i=0;i<11;i++) {
                        $('#'+$ID+' .player' + i).append($('#'+$ID+" .player-box").find("[data-id='" + options.playerData[i].ID + "']"));
                    }

                    logIfDebug(debug,'drag: success load data!','info');

                } catch (err) {
                    logIfDebug(debug,'drag: No data, use default','info');
                }
            }

            function startup() {
                applyPos();
                loadSave();
                $('#' + $ID + ' .load').on('click', function() {
                    logIfDebug(debug,'tryLoad');
                    loadSave();
                });
            }
            //==== END Function zone ====//

            var htmlConcat = "";
            if (options.formation) {
                htmlConcat+="<div class=\"form-group\"><label for=\"sel1\">Template:</label><select name=\"\" class=\"form-control formation\"></select></div>";
            }
            htmlConcat += "<div class=\"col-sm-6\"><div class=\"field-container\"><div class=\"field\">";
            for (var i = 0; i < 11; i++) {
                htmlConcat += "<ul class='player dragbox player" + i + "' data-slot='" + i + "'></ul>";
            }
            htmlConcat += "</div></div></div><div class=\"col-sm-6\"><ul class=\"player-box dragbox player-avatar\">";

            for (var i = 0; i < 11; i++) {
                htmlConcat += "<li id='" + $ID + "_con" + i + "' class='player-avatar__block playercon' draggable='true' data-id='" + $playerList[i].ID + "' style='background: url(\"" + $playerList[i].image + "\");'><span>" + $playerList[i].name + "</span></li>";
            }
            htmlConcat += "</ul></div>";
            if(debug) {
                htmlConcat += "<input class=\"loadVal\" type=\"text\" value=\"nothing\" style=\"width:100%;\">--hidden field<br/><button class=\"load\">load</button>";
            }

            $this.html(htmlConcat);

            //---Drag Zone---//
            var $draggable = $('#' + $ID + ' .dragbox');
            var $dragSwapData, $dragSwapFrom; //replace memory
            $('#' + $ID + ' .playercon').bind('dragstart', function(event) {
                event.originalEvent.dataTransfer.setData("text/plain", event.target.getAttribute('id'));
                $dragSwapData = event.originalEvent.dataTransfer.getData("text/plain");
                $dragSwapFrom = event.target.parentElement;
            });

            $draggable.bind('dragover', function(event) {
                event.preventDefault();
            });

            $draggable.bind('dragenter', function(event) {
                $(this).addClass("over");
            });

            $draggable.bind('dragleave drop', function(event) {
                $(this).removeClass("over");
            });

            //if dup inside
            $('#' + $ID + ' .playercon').bind('drop', function(event) {
                logIfDebug(debug, event.target.parentElement, 'log', 'ทับ');
                event.target.parentElement.appendChild(document.getElementById($dragSwapData));
                $($dragSwapFrom).append(event.target);
                applySave();
                $('.player').removeClass("over");
                //กันวางซ้อนข้างใน
                return false;
            });

            $('#' + $ID + ' span').bind('drop', function(event) {
                return false;
            });

            $draggable.bind('drop', function(event) {
                logIfDebug(options.debug, 'drop', 'log');
                var listitem = event.originalEvent.dataTransfer.getData("text/plain");
                event.target.appendChild(document.getElementById(listitem));
                event.preventDefault();
                logIfDebug(debug, $(event.target).hasClass('drop'));

                var canSave = applySave();
                logIfDebug(options.debug, canSave, 'log', 'saveable');
                $saveBtn.attr('disabled', !canSave);
            });
            //--- end drag zone ---//

            //--- Formation zone ---//
            if (options.formation) {
                $.each(options.formation, function(k, v) {
                    $('#' + $ID + ' .formation').append($('<option>', {
                        value: k, text: k
                    }));
                });

                //apply pos if use options.formation
                $('#' + $ID + ' .formation').change(function() {
                    for (var i = 0; i < 11; i++) {
                        options.playerData[i].x = options.formation[$(this).context.value][i].x;
                        options.playerData[i].y = options.formation[$(this).context.value][i].y;
                    }
                    applyPos();
                });
            }
            //apply pos if use select > option value
            $('#'+patternId).change(function() {
                console.log('change', $(this).val());
                var pos = $.parseJSON($(this).val());

                for (var i = 0; i < 11; i++) {
                    options.playerData[i].x = pos[i].x;
                    options.playerData[i].y = pos[i].y;
                }
                applyPos();
                applySave();
            });
            startup();
        });
    };

    $.fn.lineupArrange = function(options) {
        return this.each(function() {
            var $this = $(this),
                $ID = $this.attr('id'),
                playerData = options.playerList || defaultData;
            options.template = options.template || false;
            options.debug = options.debug || false;
            options.getDataFromHidden = options.getDataFromHidden || false;
            options.hiddenId = options.hiddenId || null;
            options.btnClass = options.btnClass || 'btn btn-sm btn-default';

            if ($ID === undefined) {
                $this.html("<h1>Error! Need ID</h1>");
                return;
            }
            else { $ID = "#"+$ID; }

            var playerHistory = {'undo': [],'redo': []};
            var field_width = 350, field_height = 520;
            var playerPos = [
                {left:175, top:480},
                {left:46, top:344},
                {left:116, top:372},
                {left:243, top:372},
                {left:296, top:344},
                {left:40, top:240},
                {left:110, top:260},
                {left:240, top:260},
                {left:300, top:240},
                {left:130, top:120},
                {left:220, top:120}
            ];

            var htmlConcat = "<div class=\"row\"><div class=\"col-sm-6\"><button class=\""+options.btnClass+" btn-undo\"><i class=\"fa fa-undo\"> Undo</i></button><button class=\""+options.btnClass+" btn-redo\"><i class=\"fa fa-rotate-right\"> Redo</i></button></div>";
            htmlConcat += "<div class=\"col-sm-6\"><label class=\"radio-inline\"><input type=\"radio\" name=\"cam\" checked=\"\" class=\"cam-p\"/>Portial Cam</label><label class=\"radio-inline\"><input type=\"radio\" name=\"cam\" class=\"cam-l\"/>Lean Cam</label></div><div class=\"col-sm-6\"></div></div>";
            htmlConcat += "<div class=\"row\">\
    <div class=\"col-sm-12\"><div class=\"field-container\"><div class=\"field\">";
            for (var i = 0; i < 11; i++) {
                htmlConcat += "<div data-slot='" + i + "' class='player player-drag player"+i+"'><div class='player__name'></div></div>";
            }

            if (options.debug) {
                htmlConcat += "</div><p class=\"log\">Log</p>";
                htmlConcat+="<hr/><p class=\"history\">Debug: </p></div></div></div><input class=\"posVal\"/><button class=\"applyVal\">load</button>";
            }

            $this.html(htmlConcat);

            function applyPos() {
                logIfDebug(options.debug, playerData, 'info', 'applying pos');
                for (var i=0;i<11;i++) {
                    $($ID+' .player'+i).css({
                        'left': playerData[i].x + '%',
                        'top': playerData[i].y + '%'
                    });
                    $($ID+' .player'+i+ ' .player__name').text(playerData[i].name);
                }
            }

            //---------Undo Redo Function----------//
            function historyRecord() {
                playerHistory['undo'].push(clonePosition());
                logIfDebug(options.debug, playerHistory, 'info', 'history');
                historyCheck();
            }

            function historyUndo() {
                //move to redo
                playerHistory['redo'].push(clonePosition());

                playerData = playerHistory['undo'][playerHistory['undo'].length - 1];
                applyPos();
                playerHistory['undo'].length--;
                historyCheck();
            }

            function historyRedo() {
                historyRecord();
                playerData = playerHistory['redo'][playerHistory['redo'].length - 1];
                playerHistory['redo'].length--;
                applyPos();
                historyCheck();
            }

            function historyCheck() {
                if (playerHistory['undo'].length < 1)
                    $($ID+' .btn-undo').prop('disabled', true);
                else
                    $($ID+' .btn-undo').prop('disabled', false);
                if (playerHistory['redo'].length < 1)
                    $($ID+' .btn-redo').prop('disabled', true);
                else
                    $($ID+' .btn-redo').prop('disabled', false);
            }

            function clonePosition() {
                var mem = [];
                for (var i = 0; i < 11; i++) {
                    mem.push({
                        x: playerData[i].x,
                        y: playerData[i].y
                    });
                }
                return mem;
            }
            $($ID+' .btn-undo').on('click', function(e) {e.preventDefault();historyUndo();});
            $($ID+' .btn-redo').on('click', function(e) {e.preventDefault();historyRedo();});

            //---------End Undo Redo Function-----------//

            //------- Debug zone -------//
            $($ID+' .applyVal').on('click', function() {
                if (options.hiddenId!==null) {
                    console.log(options.hiddenId);
                }
                logIfDebug(options.debug, $($ID+' .posVal').val(), 'info');
                playerData = $.parseJSON($($ID+' .posVal').val());
                logIfDebug(options.debug, playerData);
                for (var i=0; i<playerData.length;i++) {
                    $('.player'+i).css({'left':playerData[i].x+'%'})
                }
                applyPos();
            });
            //------- End Debug zone -------//

            $($ID+' .player').on('mousedown', function(e) {
                var $this = $(this),
                    $slot = $this.data('slot'),
                    $window = $(window),
                    mouseX = e.pageX,
                    mouseY = e.pageY,
                    width = $this.outerWidth(),
                    height = $this.outerHeight(),
                    fieldBoundLeft = $this.parent().offset().left,
                    fieldBoundRight = $this.parent().outerWidth() + fieldBoundLeft - width,
                    fieldBoundTop = $this.parent().offset().top,
                    fieldBoundBottom = $this.parent().outerHeight() + fieldBoundTop - height,
                    elemX = $this.offset().left + width - mouseX,
                    elemY = $this.offset().top + height - mouseY;

                $this.css({
                    border: '1px dashed red'
                });
                $($ID+' .player').css({
                    transition: 'none'
                });
                playerHistory['redo'] = []; //clear redo
                historyRecord();

                e.preventDefault();
                $window.on('mousemove.drag', function(e2) {
                    //--check limit of dragging--//
                    if ($slot == 0) {
                        return;
                    }
                    //calulate new XY
                    newX = e2.pageX + elemX - width;
                    newY = e2.pageY + elemY - height;

                    if (newX < fieldBoundLeft)
                        newX = fieldBoundLeft;
                    if (newX > fieldBoundRight)
                        newX = fieldBoundRight;
                    if (newY < fieldBoundTop)
                        newY = fieldBoundTop;
                    if (newY > fieldBoundBottom)
                        newY = fieldBoundBottom;

                    //---End check limit dragging---//

                    $this.offset({
                        left: newX,
                        top: newY
                    });

                    //save position
                    playerPos[$slot].left = parseInt($this.css('left'));
                    playerPos[$slot].top = parseInt($this.css('top'));
                    playerData[$slot].x = Math.round(playerPos[$slot].left * 100 / field_width);
                    playerData[$slot].y = Math.round(playerPos[$slot].top * 100 / field_height);
                    if (options.hiddenId!==null) {
                        $('#'+options.hiddenId).val(JSON.stringify(playerData));
                    }
                    $($ID+' .posVal').val(JSON.stringify(playerData));
                    $($ID+' .log').text(JSON.stringify(playerData));
                }).one('mouseup', function() {
                    $window.off('mousemove.drag');
                    $this.css({border: ''});
                    $($ID+' .player').css({
                        transition: 'all 0.3s ease'
                    });
                    applyPos();
                });
            });

            function loadPosition() {
                try {
                    playerData = $.parseJSON($('#'+options.hiddenId).val());
                    logIfDebug(options.debug,'Loaded!','info');
                    return true;
                } catch (err) {
                    $('#'+options.hiddenId).val(JSON.stringify(playerRawData));
                    logIfDebug(options.debug,'Arrange No data, use default','info');
                    return false;
                }
            }

            function startup() {
                loadPosition();
                applyPos();
                historyCheck();
                logIfDebug(options.debug, $('#'+options.hiddenId));
                logIfDebug(options.debug, playerData);
            }
            startup();

        });
    };

    function logIfDebug(debugging, text, type, title) {
        if (debugging) {
            if (title===undefined) {
                switch(type) {
                    case 'info': console.info(text); break;
                    case 'error': console.error(text); break;
                    case 'log': console.log(text); break;
                    default: console.log(text);
                }
            }
            else {
                switch (type) {
                    case 'info': console.info(title, text); break;
                    case 'error': console.error(title, text); break;
                    case 'log': console.log(title, text); break;
                    default: console.log(title, text);
                }
            }
        }
    }

})(jQuery);


$(document).ready(function() {
    $('.cam-l').on('click', function() {
        $('.field').css({
            'transform': 'rotateX(40deg)',
            'box-shadow': 'grey 2px 9px 10px'
        });
        $('.player').css({
            'transform': 'translate(-50%, -50%) rotateX(-10deg)'
        });
    });
    $('.cam-p').on('click', function() {
        $('.field').css({
            'transform': 'none',
            'box-shadow': ''
        });
        $('.player').css({
            'transform': 'translate(-50%, -50%)'
        });
    });
    $('.cam-s1').on('click', function() {
        $('.field').css({
            'transform': 'rotateZ(90deg)',
            'box-shadow': ''
        });
        $('.player').css({
            'transform': 'translate(-50%, -50%) rotateZ(-90deg)'
        });
    });
    $('.cam-rt').on('click', function() {
        var zCam = 0;
        $('.field').css({
            'transition': ''
        });
        var interval = setInterval(function() {
            //$('.field').css({'transform': 'rotateX(40deg) rotateZ('+zCam+'deg)', 'box-shadow': 'grey 2px 9px 10px'});
            $('.player').css({
                'transform': 'translate(-50%, -50%) rotateX(-10deg) rotateY(-' + zCam + 'deg) rotateZ(-' + zCam + 'deg)'
            });
            zCam += 10;
        }, 600);
    });

});
