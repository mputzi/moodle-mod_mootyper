function isCombined(chr) {
    return (chr === '´' || chr === '`');
}

THE_LAYOUT = 'Spanish(V3)';

function keyupCombined(e) {
    if (ended) {
        return false;
    }
    if (!started) {
        doStart();
    }
    var keychar = getPressedChar(e);
//    if (keychar === '[not_yet_defined]') {
    if (keychar === 'á') {
        combinedChar = true;
        return true;
    }
    if (combinedCharWait) {
        combinedCharWait = false;
        return true;
    }
    var currentText = $('#tb1').val();
    var lastChar = currentText.substring(currentText.length - 1);
    if (combinedChar && lastChar === currentChar) {
        if (show_keyboard) {
            var thisE = new keyboardElement(currentChar);
            thisE.turnOff();
        }
        if (currentPos === fullText.length - 1) {   // END.
            doTheEnd();
            return true;
        }
        if (currentPos < fullText.length - 1) {
            var nextChar = fullText[currentPos + 1];
            if (show_keyboard){
                var nextE = new keyboardElement(nextChar);
                nextE.turnOn();
            }
            if (!isCombined(nextChar)) {            // If next char is not combined char.
                $("#form1").off("keyup", "#tb1");
                $("#form1").on("keypress", "#tb1", keyPressed);
            }
        }
        combinedChar = false;
        moveCursor(currentPos + 1);
        currentChar = fullText[currentPos + 1];
        currentPos++;
        return true;
    } else {
        combinedChar = false;
        napake++;
        var tbval = $('#tb1').val();
        $('#tb1').val(tbval.substring(0, currentPos));
        return false;
    }
}

function keyupFirst(event) {
    $("#form1").off("keyup", "#tb1", keyupFirst);
    $("#form1").on("keyup", "#tb1", keyupCombined);
    return false;
}

function keyboardElement(ltr) {
    this.chr = ltr.toLowerCase();
    this.alt = false;
    this.accent = false;
    if (isLetter(ltr)) {
        this.shift = ltr.toUpperCase() === ltr && ltr != '¡';
    } else {
        // @codingStandardsIgnoreLine
        if (ltr.match(/[ª<!"·$%&/()=?¿^*¨;:_]/i)) {
            this.shift = true;
        } else {
            this.shift = false;
        }
    }
    // @codingStandardsIgnoreLine
    if (ltr.match(/[áéíóú]/)) {
        this.shift = false;
        this.alt = false;
        this.accent = true;
    }
    // @codingStandardsIgnoreLine
    if (ltr.match(/[ÁÉÍÓÚ]/)) {
        this.shift = true;
        this.alt = false;
        this.accent = true;
    }
    if (ltr === 'ü') {
        this.shift = true;
        this.alt = false;
        this.accent = true;
    }
    if (ltr === 'Ü') {
        this.shift = true;
        this.alt = false;
        this.accent = true;
    }
		// @codingStandardsIgnoreLine
        if (ltr.match(/[\\|@#~€¬\[\]{}]/i)) {
        this.shift = false;
        this.alt = true;
        this.accent = false;
    }
    this.turnOn = function () {
        if (isLetter(this.chr)) {
            document.getElementById(getKeyID(this.chr)).className = "next" + thenFinger(this.chr.toLowerCase());
        } else if (this.chr === ' ') {
            document.getElementById(getKeyID(this.chr)).className = "nextSpace";
        } else {
            document.getElementById(getKeyID(this.chr)).className = "next" + thenFinger(this.chr.toLowerCase());
        }
        if (this.chr === '\n' || this.chr === '\r\n' || this.chr === '\n\r' || this.chr === '\r') {
            document.getElementById('jkeyenter').className = "next4";
        }
        if (this.shift) {
            document.getElementById('jkeyshiftd').className = "next4";
            document.getElementById('jkeyshiftl').className = "next4";
        }
        if (this.alt) {
            document.getElementById('jkeyaltgr').className = "next2";
        }
        if (this.accent) {
            document.getElementById('jkeyrighttick').className = "next4";
        }
    };
    this.turnOff = function () {
        if (isLetter(this.chr)) {
        // @codingStandardsIgnoreLine
            if (this.chr.match(/[asdfjklñ]/i)) {
                document.getElementById(getKeyID(this.chr)).className = "finger" + thenFinger(this.chr.toLowerCase());
            } else {
                document.getElementById(getKeyID(this.chr)).className = "normal";
            }
        } else {
            document.getElementById(getKeyID(this.chr)).className = "normal";
        }
        if (this.chr === '\n' || this.chr === '\r\n' || this.chr === '\n\r' || this.chr === '\r') {
            document.getElementById('jkeyenter').classname = "normal";
        }
        if (this.shift) {
            document.getElementById('jkeyshiftd').className = "normal";
            document.getElementById('jkeyshiftl').className = "normal";
        }
        if (this.alt) {
            document.getElementById('jkeyaltgr').className = "normal";
        }
        if (this.accent) {
            document.getElementById('jkeyrighttick').className = "normal";
        }
    };
}

function thenFinger(t_crka) {
    if (t_crka === ' ') {
        return 5; // Highlight the spacebar.
    // @codingStandardsIgnoreLine
    } else if (t_crka.match(/[ºª\\1!|qaá<>0=pñ.:\'?`^\[´¨{\-_¡¿+*\]ç}]/i)) {
        return 4; // Highlight the correct key above in red.
    // @codingStandardsIgnoreLine
    } else if (t_crka.match(/[2"@wsz9)oól,;]/i)) {
        return 3; // Highlight the correct key above in green.
    // @codingStandardsIgnoreLine
    } else if (t_crka.match(/[3·#eé€dx8(iíkm]/i)) {
        return 2; // Highlight the correct key above in yellow.    // @codingStandardsIgnoreLine
    } else if (t_crka.match(/[4$~rfc5%€tgv6&¬yhb7/uúüjn]/i)) {
        return 1; // Highlight the correct key above in blue.
    } else {
        return 6; // Do not change any highlight.
    }
}

function getKeyID(t_crka) {
    if (t_crka === ' ') {
        return "jkeyspace";
    } else if (t_crka === ',' || t_crka === ';') {
        return "jkeycomma";
    } else if (t_crka === '\n') {
        return "jkeyenter";
    } else if (t_crka === '.' || t_crka === ':') {
        return "jkeyperiod";
    } else if (t_crka === '-' || t_crka === '_') {
        return "jkeyminus";
    } else if (t_crka === '!' || t_crka === '|') {
        return "jkey1";
    } else if (t_crka === '"' || t_crka === '@') {
        return "jkey2";
    } else if (t_crka === '·' || t_crka === '#') {
        return "jkey3";
    } else if (t_crka === '$'|| t_crka === '~') {
        return "jkey4";
    } else if (t_crka === '%') {
        return "jkey5";
    } else if (t_crka === '&' || t_crka === '¬') {
        return "jkey6";
    } else if (t_crka === '/') {
        return "jkey7";
    } else if (t_crka === '(') {
        return "jkey8";
    } else if (t_crka === ')') {
        return "jkey9";
    } else if (t_crka === '=') {
        return "jkey0";
    } else if (t_crka === '`' || t_crka === '^' || t_crka === '[') {
        return "jkeylefttick";
    } else if (t_crka === '´' || t_crka === '¨' || t_crka === '{') {
        return "jkeyrighttick";
    } else if (t_crka === 'ç' || t_crka === '}') {
        return "jkeyç";
    } else if (t_crka === "'" || t_crka === '?') {
        return "jkeyapostrophe";
    } else if (t_crka === '*' || t_crka === '+' || t_crka === ']') {
        return "jkeyplus";
    } else if (t_crka === '<' || t_crka === '>') {
        return "jkeyckck";
    } else if (t_crka === 'º' || t_crka === 'ª' || t_crka === '\\') {
        return "jkeytilde";
    } else if (t_crka === '¿') {
        return 'jkey¡';
    } else if (t_crka === 'a' || t_crka === 'á') {
        return "jkeya";
    } else if (t_crka === 'e' || t_crka === 'é' || t_crka === '€') {
        return "jkeye";
    } else if (t_crka === 'i' || t_crka === 'í') {
        return "jkeyi";
    } else if (t_crka === 'o' || t_crka === 'ó') {
        return "jkeyo";
    } else if (t_crka === 'u' || t_crka === 'ú' || t_crka === 'ü') {
        return "jkeyu";
    } else {
        return "jkey" + t_crka;
    }
}

function isLetter(str) {
    return str.length === 1 && str.match(/[a-z¡ñçáéíóúü]/i);
}
