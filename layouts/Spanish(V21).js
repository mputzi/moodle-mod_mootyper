function isCombined(chr) {
    return (chr === '´' || chr === '`');
}

THE_LAYOUT = 'Spanish(V2.1)';

function keyupCombined(e) {
    if (ended) {
        return false;
    }
    if (!started) {
        doStart();
    }
    var keychar = getPressedChar(e);
    if (keychar === '[not_yet_defined]') {
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
            doKonec();
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

THE_LAYOUT = 'Spanish';

function keyboardElement(ltr) {
    this.chr = ltr.toLowerCase();
    this.alt = false;
    this.accent = false;
    if (isLetter(ltr)) {
        this.shift = ltr.toUpperCase() === ltr && ltr != '¡';
    } else {
        if (ltr === 'ª' || ltr === '<' || ltr === '!' || ltr === '"' || ltr === '·' || ltr === '$' || ltr === '%' ||
            ltr === '&' || ltr === '/' || ltr === '(' || ltr === ')' || ltr === '=' || ltr === '?' || ltr === '¿' ||
            ltr === '^' || ltr === '*' || ltr === '¨' || ltr === ';' || ltr === ':' || ltr === '_') {
            this.shift = true;
        } else {
            this.shift = false;
        }
        if (ltr === '\\' || ltr === '|' || ltr === '@' || ltr === '#' || ltr === '~' || ltr === '€' || ltr === '¬' || ltr === '[' || ltr === ']' || ltr === '{' || ltr === '}') {
            this.alt = true;
        }
    }
    if (ltr === 'á' || ltr === 'é' || ltr === 'í' || ltr === 'ó' || ltr === 'ú') {
        this.shift = false;
        this.alt = false;
        this.accent = true;
    }
    if (ltr === 'Á' || ltr === 'É' || ltr === 'Í' || ltr === 'Ó'|| ltr === 'Ú') {
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
    if (ltr === '€' || ltr === '\\' || ltr === '|' || ltr === '@' || ltr === '#' || ltr === '~' || ltr === '¬' || ltr === '[' || ltr === ']' || ltr === '{' || ltr === '}') {
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
            if (this.chr === 'a' || this.chr === 's' || this.chr === 'd' || this.chr === 'f' ||
                this.chr === 'j' || this.chr === 'k' || this.chr === 'l' || this.chr === 'ñ') {
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
    } else if (t_crka === 'º' || t_crka === 'ª' || t_crka === '\\' || t_crka === '1' ||  t_crka === '!' || t_crka === '|' ||
               t_crka === 'q' || t_crka === 'a' || t_crka === 'á' ||  t_crka === '<' || t_crka === '>' ||
               t_crka === '0' ||t_crka === '=' || t_crka === 'p' || t_crka === 'ñ' || t_crka === ':' || t_crka === '.' ||
               t_crka === '\'' || t_crka === '?' || t_crka === '`' || t_crka === '^' || t_crka === '[' ||
               t_crka === '´' || t_crka === '¨' || t_crka === '{' || t_crka === '-' || t_crka === '_' ||
               t_crka === '¡' || t_crka === '¿' || t_crka === '+' || t_crka === '*' || t_crka === ']' || t_crka === 'ç' ||t_crka === '}') {
        return 4; // Highlight the correct key above in red.
    } else if (t_crka === '2' || t_crka === '"' || t_crka === '@' || t_crka === 'w' || t_crka === 's' || t_crka === 'z' ||
               t_crka === '9' || t_crka === ')' || t_crka === 'o' || t_crka === 'ó' || t_crka === 'l' || t_crka === ',' || t_crka === ';') {
        return 3; // Highlight the correct key above in green.
    } else if (t_crka === '3' || t_crka === '·' || t_crka === '#' || t_crka === 'e' || t_crka === 'é' || t_crka === 'd' || t_crka === 'x' ||
               t_crka === '8' || t_crka === '(' || t_crka === 'i' || t_crka === 'í' || t_crka === 'k' || t_crka === 'm') {
        return 2; // Highlight the correct key above in yellow.
    } else if (t_crka === '4' || t_crka === '$' || t_crka === '~' || t_crka === 'r' || t_crka === 'f' || t_crka === 'c' ||
               t_crka === '5' || t_crka === '%' || t_crka === '€' || t_crka === 't' || t_crka === 'g' || t_crka === 'v' ||
               t_crka === '6' || t_crka === '&' || t_crka === '¬' || t_crka === 'y' || t_crka === 'h' || t_crka === 'b' ||
               t_crka === '7' || t_crka === '/' || t_crka === 'u' || t_crka === 'ú' || t_crka === 'ü' || t_crka === 'j' || t_crka === 'n') {
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
    } else if (t_crka === '%' || t_crka === '€') {
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
