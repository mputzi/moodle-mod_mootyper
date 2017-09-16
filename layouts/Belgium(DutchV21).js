function isCombined(chr) {
    return (chr === '´' || chr === '`');
}

THE_LAYOUT = 'Belgium(DutchV2.1)';

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
        if (ltr === '³' || ltr === '<' || ltr === '1' || ltr === '2' || ltr === '3' || ltr === '4' || ltr === '5' ||
            ltr === '6' || ltr === '7' || ltr === '8' || ltr === '9' || ltr === '0' || ltr === '°' || ltr === '_' ||
            ltr === '¨' || ltr === '*' || ltr === '%' || ltr === '£' || ltr === '?' || ltr === '.' || ltr === '/' || ltr === '+') {
            this.shift = true;
        } else {
            this.shift = false;
        }

    }
    if (ltr === '\\' || ltr === '|' || ltr === '@' || ltr === '#' || ltr === '€' || ltr === '{' || ltr === '}' || ltr === '[' || ltr === ']' || ltr === '~') {
        this.shift = false;
        this.alt = true;
        this.accent = false;
    }
    if (ltr === 'ë') {
        this.shift = true;
        this.alt = false;
        this.accent = false;
    }
    this.turnOn = function () {
        if (isLetter(this.chr)) {
            document.getElementById(thenPressId(this.chr)).className = "next" + dobiFinger(this.chr.toLowerCase());
        } else if (this.chr === ' ') {
            document.getElementById(thenPressId(this.chr)).className = "nextSpace";
        } else {
            document.getElementById(thenPressId(this.chr)).className = "next" + dobiFinger(this.chr.toLowerCase());
        }
        if (this.chr === 'ë') {
            document.getElementById('jkeycaret').className = "next4";
            document.getElementById('jkeyshiftd').className = "next4";
            document.getElementById('jkeyshiftl').className = "next4";
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
            if (this.chr === 'q' || this.chr === 's' || this.chr === 'd' || this.chr === 'f' ||
                this.chr === 'j' || this.chr === 'k' || this.chr === 'l' || this.chr === 'm') {
                document.getElementById(thenPressId(this.chr)).className = "finger" + dobiFinger(this.chr.toLowerCase());
            } else {
                document.getElementById(thenPressId(this.chr)).className = "normal";
            }
        } else {
            document.getElementById(thenPressId(this.chr)).className = "normal";
        }
        if (this.chr === 'ë') {
            document.getElementById('jkeycaret').className = "normal";
            document.getElementById('jkeyshiftd').className = "normal";
            document.getElementById('jkeyshiftl').className = "normal";
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

function dobiFinger(t_crka) {
    if (t_crka === ' ') {
        return 5; // Highlight the spacebar.
    } else if (t_crka === '²' || t_crka === '³' || t_crka === '&' ||  t_crka === '1' || t_crka === '|' ||
               t_crka === 'a' || t_crka === 'q' || t_crka === 'á' ||  t_crka === '<' || t_crka === '>' || t_crka === '\\' ||
               t_crka === 'à' ||t_crka === '0' || t_crka === '}' || t_crka === 'p' || t_crka === 'm' || t_crka === ':' || t_crka === '/' ||
               t_crka === ')' || t_crka === '°' || t_crka === '`' || t_crka === '^' || t_crka === '¨' || t_crka === '[' ||
               t_crka === 'ù' || t_crka === '%' || t_crka === '´' || t_crka === '=' || t_crka === '+' || t_crka === '~' ||
               t_crka === '-' || t_crka === '_' || t_crka === '$' || t_crka === '*' || t_crka === ']' || t_crka === 'µ' || t_crka === '£' || t_crka === '`') {
        return 4; // Highlight the correct key above in red.
    } else if (t_crka === 'é' || t_crka === '2' || t_crka === '@' || t_crka === 'z' || t_crka === 's' || t_crka === 'w' ||
               t_crka === 'ç' || t_crka === '9' || t_crka === '{' || t_crka === 'o' || t_crka === 'ó' || t_crka === 'l' || t_crka === ';' || t_crka === '.') {
        return 3; // Highlight the correct key above in green.
    } else if (t_crka === '"' || t_crka === '3' || t_crka === '#' || t_crka === 'e' || t_crka === 'é' || t_crka === 'ë' || t_crka === '€' ||
               t_crka === 'd' || t_crka === 'x' || t_crka === '!' || t_crka === '8' || t_crka === 'i' || t_crka === 'í' || t_crka === 'k' ||
               t_crka === ',' || t_crka === '?') {
        return 2; // Highlight the correct key above in yellow.
    } else if (t_crka === '\'' || t_crka === '4' || t_crka === 'r' || t_crka === 'f' || t_crka === 'c' ||
               t_crka === '(' || t_crka === '5' || t_crka === 't' || t_crka === 'g' || t_crka === 'v' ||
               t_crka === '§' || t_crka === '6' ||t_crka === 'y' || t_crka === 'h' || t_crka === 'b' ||
               t_crka === 'è' || t_crka === '7' || t_crka === 'u' || t_crka === 'ú' || t_crka === 'ü' || t_crka === 'j' || t_crka === 'n') {
        return 1; // Highlight the correct key above in blue.
    } else {
        return 6;
    }
}

function thenPressId(t_crka) {
    if (t_crka === ' ') {
        return "jkeyspace";
    } else if (t_crka === '\n') {
        return "jkeyenter";
    } else if (t_crka === ',' || t_crka === '?') {
        return "jkeycomma";
    } else if (t_crka === ';' || t_crka === '.') {
        return "jkeysemicolon";
    } else if (t_crka === ':' || t_crka === '/') {
        return "jkeycolon";
    } else if (t_crka === '-' || t_crka === '_') {
        return "jkeyminus";
    } else if (t_crka === '=' || t_crka === '+' || t_crka === '~') {
        return "jkeyequal";
    } else if (t_crka === '&' || t_crka === '|') {
        return "jkey1";
    } else if (t_crka === 'é' || t_crka === '@') {
        return "jkey2";
    } else if (t_crka === '"' || t_crka === '#') {
        return "jkey3";
    } else if (t_crka === '\'') {
        return "jkey4";
    } else if (t_crka === '(') {
        return "jkey5";
//    } else if (t_crka === '§' || t_crka === '^') {
//        return "jkey6";
    } else if (t_crka === '§') {
        return "jkey6";
    } else if (t_crka === 'è') {
        return "jkey7";
    } else if (t_crka === '!') {
        return "jkey8";
    } else if (t_crka === 'ç' || t_crka === '{') {
        return "jkey9";
    } else if (t_crka === 'à' || t_crka === '}') {
        return "jkey0";
    } else if (t_crka === '^' || t_crka === '¨' || t_crka === '[') {
        return "jkeycaret";
    } else if ( t_crka === '$' || t_crka === '*' || t_crka === ']') {
        return "jkeydollar";
    } else if (t_crka === '%' || t_crka === '´') {
        return "jkeyù";
    } else if (t_crka === '£' || t_crka === '`') {
        return "jkeyµ";
    } else if (t_crka === ')' || t_crka === '°') {
        return "jkeyparenr";
    } else if (t_crka === '<' || t_crka === '>') {
        return "jkeyckck";
    } else if (t_crka === '²' || t_crka === '³') {
        return "jkeytildo";
    } else if (t_crka === 'a' || t_crka === 'á') {
        return "jkeya";
    } else if (t_crka === '¨') {
        return "jkeycaret";
    } else if (t_crka === '€' || t_crka === 'é' || t_crka === 'ë') {
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
    return str.length === 1 && str.match(/[0-9a-z¡ñçáéíóúüùµ]/i);
}
