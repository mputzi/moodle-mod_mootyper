var startTime,
    endTime,
    mistakes,
    currentPos,
    started = false,
    ended = false,
    currentChar,
    fullText,
    intervalID = -1,
    interval2ID = -1,
    appUrl,
    showKeyboard,
    THE_LAYOUT,
    continuousType,
    countMistypedSpaces,
    keyupCombined,
    keyupFirst;

/**
 * If not the end of fullText, move cursor to next character.
 *
 * @param {number} nextPos.
 */
function moveCursor(nextPos) {
    if (nextPos > 0 && nextPos <= fullText.length) {
        $('#crka' + (nextPos - 1)).addClass('txtGreen');
        $('#crka' + (nextPos - 1)).removeClass('txtBlue');
        $('#crka' + (nextPos - 1)).removeClass('txtRed');
    }
    if (nextPos < fullText.length) {
        $('#crka' + nextPos).addClass('txtBlue');
    }
}

// See about adding a scrollit function here.
// Like the text works in Roshine.

/**
 * End of typing.
 *
 */
function doTheEnd() {
    $('#crka' + (fullText.length - 1)).addClass('txtGreen');
    $('#crka' + (fullText.length - 1)).removeClass('txtBlue');
    $('#crka' + (fullText.length - 1)).removeClass('txtRed');
    ended = true;
    clearInterval(intervalID);
    clearInterval(interval2ID);
    endTime = new Date();
    differenceT = timeDifference(startTime, endTime);
    var hours = differenceT.getHours();
    var mins = differenceT.getMinutes();
    var secs = differenceT.getSeconds();
    var samoSekunde = converToSeconds(hours, mins, secs);
    $('input[name="rpFullHits"]').val((fullText.length + mistakes));
    $('input[name="rpTimeInput"]').val(samoSekunde);
    $('input[name="rpMistakesInput"]').val(mistakes);
    var speed = calculateSpeed(samoSekunde);
    $('input[name="rpAccInput"]').val(calculateAccuracy(fullText, mistakes).toFixed(2));
    $('input[name="rpSpeedInput"]').val(speed);
    $('#tb1').attr('disabled', 'disabled');
    $('#btnContinue').css('visibility', 'visible');
    var wpm = (speed / 5) - mistakes;
    $('#jsWpm').html(wpm.toFixed(2));
    $('#jsWpm2').html(wpm.toFixed(2));

    var rpAttId = document.form1.rpAttId.value;

    var juri = appUrl + "/mod/mootyper/atchk.php?status=3&attemptid=" + $('input[name="rpAttId"]').val();
    $.get(juri, function(data) { });
}

/**
 * Get the character for the pressed key depending on current keyboard driver.
 *
 * @param {char} e.
 * @returns {keychar}.
 */
function getPressedChar(e) {
    var keynum;
    var keychar;
    var numcheck;

    if (window.event) {  // IE.
        keynum = e.keyCode;
    } else if (e.which) {  // Netscape/Firefox/Opera.
        keynum = e.which;
    }
    if (keynum === 13) {
        keychar = '\n';
        // This hack is needed for Spanish keyboard, which uses 161 for some character.
    } else if ((!keynum || keynum === 160 || keynum === 161) && (keynum !== 161 && THE_LAYOUT !== 'Spanish')) {
        keychar = '[not_yet_defined]';
    } else {
        keychar = String.fromCharCode(keynum);
    }
    return keychar;
}

/**
 * Set the focus.
 *
 * @param {char} e.
 * @returns {bolean}.
 */
function focusSet(e) {
    if(!started) {
        $('#tb1').val('');
        if (showKeyboard){
            var thisEl = new keyboardElement(fullText[0]);
            thisEl.turnOn();
        }
        return true;
    }
    else{
        $('#tb1').val(fullText.substring(0, currentPos));
        return true;
    }
}

/**
 * Do checks.
 *
 */
function doCheck() {
    var rpMootyperId = $('input[name="rpSityperId"]').val();
    var rpUser = $('input[name="rpUser"]').val();
    var rpAttId = $('input[name="rpAttId"]').val();
    var juri = appUrl + "/mod/mootyper/atchk.php?status=2&attemptid=" + rpAttId + "&mistakes=" + mistakes + "&hits=" + (currentPos + mistakes);
    $.get(juri, function( data ) { });
}

/**
 * Start exercise and reset data variables.
 *
 */
function doStart() {
    startTime = new Date();
    mistakes = 0;
    currentPos = 0;
    started = true;
    currentChar = fullText[currentPos];
    intervalID = setInterval('updTimeSpeed()', 1000);
    var rpMootyperId = $('input[name="rpSityperId"]').val();
    var rpUser = $('input[name="rpUser"]').val();
    var juri = appUrl + "/mod/mootyper/atchk.php?status=1&mootyperid=" + rpMootyperId + "&userid=" + rpUser + "&time=" + (startTime.getTime() / 1000);
    $.get(juri, function( data ) {
        $('input[name="rpAttId"]').val(data);
    });
    interval2ID = setInterval('doCheck()', 4000);
}

/**
 * Process current key press and proceed based on typing mode.
 *
 * @param {char} e.
 * @returns {bolean}.
 */
function keyPressed(e) {
    if (ended) {
        return false;
    }
    if (!started) {
        doStart();
    }

    var keychar = getPressedChar(e);
    if (keychar === currentChar || ((currentChar === '\n' || currentChar === '\r\n' || currentChar === '\n\r' || currentChar === '\r') && (keychar === ' '))) {
        if(currentPos === fullText.length - 1) {  // Student is at the end of the exercise.
            $('#tb1').val($('#tb1').val() + currentChar);
            var elemOff = new keyboardElement(currentChar);
            elemOff.turnOff();
            doTheEnd();
            return true;
        }

        if (currentPos < fullText.length - 1) {  // Student still has more to type.
            var nextChar = fullText[currentPos + 1];
            if (showKeyboard) {
                var thisE = new keyboardElement(currentChar);
                thisE.turnOff();
                if (isCombined(nextChar) && (thisE.shift || thisE.alt || thisE.pow || thisE.uppercase_umlaut || thisE.accent)) {
                    combinedCharWait = true;
                }
                var nextE = new keyboardElement(nextChar);
                nextE.turnOn();
            }
            if (isCombined(nextChar)) {
                $("#form1").off("keypress", "#tb1", keyPressed);
                $("#form1").on("keyup", "#tb1", keyupFirst);
            }
        }
        moveCursor(currentPos + 1);
        currentChar = fullText[currentPos + 1];
        currentPos++;
        return true;
    } else if (keychar === ' ' && !countMistypedSpaces) { // Ignore mistyped extra spaces unless set to count them.
        return false;
    } else {
        mistakes++; // Typed the wrong letter so increment mistake count.
        if ((!continuousType && !countMistypedSpaces) || (!continuousType && countMistypedSpaces)) { // If not set for continuous typing, wait for correct letter.
            return false;
        } else if (currentPos < fullText.length - 1) { // If continuous typing, show wrong letter and move on.
                var nextChar = fullText[currentPos + 1];
            if (showKeyboard) {
                    var thisE = new keyboardElement(currentChar);
                    thisE.turnOff();
                if (isCombined(nextChar) && (thisE.shift || thisE.alt || thisE.pow || thisE.uppercase_umlaut || thisE.accent)) {
                        combinedCharWait = true;
                }
                    var nextE = new keyboardElement(nextChar);
                    nextE.turnOn();
            }
            if (isCombined(nextChar)) {
                $("#form1").off("keypress", "#tb1", keyPressed);
                $("#form1").on("keyup", "#tb1", keyupFirst);
            }
        }
        moveCursor(currentPos + 1);
        currentChar = fullText[currentPos + 1];
        currentPos++;
        return true;
    }
}

/**
 * Calculate time to seconds.
 *
 * @param {number} hrs.
 * @param {number} mins.
 * @param {number} seccs.
 * @returns {seconds}.
 */
function converToSeconds(hrs, mins, seccs) {
    if (hrs > 0) {
        mins = (hrs * 60) + mins;
    }
    if (mins === 0) {
        return seccs;
    } else {
        return (mins * 60) + seccs;
    }
}

/**
 * Calculate date difference.
 *
 * @param {number} t1.
 * @param {number} t2.
 * @returns {date}.
 */
function timeDifference(t1, t2) {
    var yrs = t1.getFullYear();
    var mnth = t1.getMonth();
    var dys = t1.getDate();
    var h1 = t1.getHours();
    var m1 = t1.getMinutes();
    var s1 = t1.getSeconds();
    var h2 = t2.getHours();
    var m2 = t2.getMinutes();
    var s2 = t2.getSeconds();
    var ure = h2 - h1;
    var minute = m2 - m1;
    var secunde = s2 - s1;
    return new Date(yrs, mnth, dys, ure, minute, secunde, 0);
}

/**
 * Initialize variables and text to enter.
 *
 * @param {varchar} ttext.
 * @param {number} tinprogress.
 * @param {number} tmistakes.
 * @param {number} thits.
 * @param {number} tstarttime.
 * @param {number} tattemptid.
 * @param {varchar} turl.
 * @param {boolean} tshowkeyboard.
 * @param {boolean} tcontinuoustype.
 * @param {boolean} tcountmistypedspaces.
 */
function inittexttoenter(ttext, tinprogress, tmistakes, thits, tstarttime, tattemptid, turl, tshowkeyboard, tcontinuoustype, tcountmistypedspaces) {
    $("#form1").on("keypress", "#tb1", keyPressed);
    showKeyboard = tshowkeyboard;
    continuousType = tcontinuoustype;
    countMistypedSpaces = tcountmistypedspaces;
    fullText = ttext;
    appUrl = turl;
    var tempStr = "";
    if (tinprogress) {
        $('input[name="rpAttId"]').val(tattemptid);
        startTime = new Date(tstarttime * 1000);
        mistakes = tmistakes;
        currentPos = (thits - tmistakes);
        currentChar = fullText[currentPos]; // Current character (trenutni = current).
        if(showKeyboard) {
            var nextE = new keyboardElement(currentChar);
            nextE.turnOn();
            if(isCombined(currentChar)) {
                $("#form1").off("keypress", "#tb1", keyPressed);
                $("#form1").on("keyup", "#tb1", keyupCombined);
            }
        }
        started = true;
        intervalID = setInterval('updTimeSpeed()', 1000);
        interval2ID = setInterval('doCheck()', 3000);
        for (var i = 0; i < currentPos; i++) {
            var tChar = ttext[i];
            if (tChar === '\n') {
                tempStr += "<span id='crka" + i + "' class='txtGreen'>&darr;</span><br>";
            } else {
                tempStr += "<span id='crka" + i + "' class='txtGreen'>" + tChar + "</span>";
            }
        }
        tempStr += "<span id='crka" + currentPos + "' class='txtBlue'>" + currentChar + "</span>";
        for (var j = currentPos + 1; j < ttext.length; j++) {
            var tChar = ttext[j];
            if (tChar === '\n') {
                tempStr += "<span id='crka" + j + "' class='txtRed'>&darr;</span><br>";
            } else {
                tempStr += "<span id='crka" + j + "' class='txtRed'>" + tChar + "</span>";
            }
        }
    } else {
        for (var i = 0; i < ttext.length; i++) {
            var tChar = ttext[i];
            if (i === 0) {
                tempStr += "<span id='crka" + i + "' class='txtBlue'>" + tChar + "</span>";
                if (isCombined(tChar)) {
                    $("#form1").off("keypress", "#tb1", keyPressed);
                    $("#form1").on("keyup", "#tb1", keyupCombined);
                }
            } else if (tChar === '\n') {
                tempStr += "<span id='crka" + i + "' class='txtRed'>&darr;</span><br>";
            } else {
                tempStr += "<span id='crka" + i + "' class='txtRed'>" + tChar + "</span>";
            }
        }
    }
    $('#texttoenter').html(tempStr);
}
/** This function does not appear to be used.
 * Verified English and Spanish so far. 10/3/17.
 * function isDigit(aChar) {
 *     myCharCode = aChar.charCodeAt(0);
 *     if ((myCharCode > 47) && (myCharCode < 58)) {
 *         return true;
 *     }
 *     return false;
 * }
 */

/**
 * Calculate speed.
 *
 * @param {number} sc.
 * @returns {number}.
 */
function calculateSpeed(sc) {
    return (((currentPos + mistakes) * 60) / sc);
}

/**
 * Calculate accuracy.
 *
 * @param {number} currentPos.
 * @param {number} mistakes.
 * @returns {number}.
 */
function calculateAccuracy() {
    if (currentPos + mistakes === 0) {
        return 0;
    }
    return ((currentPos * 100) / (currentPos + mistakes));
}

/**
 * Update current time, progress, mistakes presicsion, hits per minute, and words per minute.
 *
 * @param {number} startTime.
 * @param {number} newCas.
 * @param {number} secs.
 * @param {number} tDifference.
 * @param {number} mistakes.
 * @param {number} currentPos.
 * @param {number} fullText.
 * @param {number} currentPos.
 */
function updTimeSpeed() {
    newCas = new Date();
    tDifference = timeDifference(startTime, newCas);
    var secs = converToSeconds(tDifference.getHours(), tDifference.getMinutes(), tDifference.getSeconds());
    $('#jsTime').html(secs);
// Temp comment out time in seconds to use min and sec.
//    $('#jsTime2').html(secs);
// Need to see about adding leading 0 for <10 min and sec.
    $('#jsTime2').html(tDifference.getMinutes()+':'+tDifference.getSeconds());

    $('#jsProgress').html(currentPos + "/" + fullText.length);
    $('#jsProgress2').html(currentPos + "/" + fullText.length);

    $('#jsMistakes').html(mistakes);
    $('#jsMistakes2').html(mistakes);

    $('#jsSpeed').html(calculateSpeed(secs).toFixed(2));
    $('#jsSpeed2').html(calculateSpeed(secs).toFixed(2));

    $('#jsAcc').html(calculateAccuracy(fullText, mistakes).toFixed(2));
    $('#jsAcc2').html(calculateAccuracy(fullText, mistakes).toFixed(2));
}
