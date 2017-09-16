function isCombined(chr) {
    return false;
}

function keyupCombined(e) {
    return false;
}

function keyupFirst(event) {
    return false;
}

THE_LAYOUT = 'Portuguese(V2.1)(Brazil)';

function keyboardElement(ltr) {
    this.chr = ltr.toLowerCase();
    this.alt = false;
    if (isLetter(ltr)) {
        this.shift = ltr.toUpperCase() === ltr;
    } else {
        if (ltr === '"' || ltr === '!' || ltr === '@' || ltr === '#' || ltr === '$' || ltr === '%' || ltr === '¨' ||
            ltr === '&' || ltr === '*' || ltr === '(' || ltr === ')' || ltr === '_' || ltr === '+' ||
            ltr === '`' || ltr === '{' || ltr === '^' || ltr === '}' ||
            ltr === '|' || ltr === '<' || ltr === '>' || ltr === ':' || ltr === '?') {
            this.shift = true;
        } else {
            this.shift = false;
        }
    }
    if (ltr === '¹' || ltr === '²' || ltr === '³' || ltr === '£' || ltr === '¢' || ltr === '¬' || ltr === '§' ||
        ltr === '/' || ltr === '°' || ltr === 'ª' || ltr === 'º') {
        this.shift = false;
        this.alt = true;
    }
    this.turnOn = function () {
        if (isLetter(this.chr)) {
            document.getElementById(thenPressId(this.chr)).className = "next" + thenFinger(this.chr.toLowerCase());
        } else if (this.chr === ' ') {
            document.getElementById(thenPressId(this.chr)).className = "nextSpace";
        } else {
            document.getElementById(thenPressId(this.chr)).className = "next" + thenFinger(this.chr.toLowerCase());
        }
        if (this.chr === '\n' || this.chr === '\r\n' || this.chr === '\n\r' || this.chr === '\r') {
            document.getElementById('jkeyenter').className = "next4";
        }
        if (this.shift) {
            document.getElementById('jkeyshiftd').className = "next4";
            document.getElementById('jkeyshiftl').className = "next4";
        }
        if (this.alt) {
            document.getElementById('jkeyaltgr').className = "nextSpace";
        }
    };
    this.turnOff = function () {
        if (isLetter(this.chr)) {
            if (this.chr === 'a' || this.chr === 's' || this.chr === 'd' || this.chr === 'f' ||
                this.chr === 'j' || this.chr === 'k' || this.chr === 'l' || this.chr === 'ç') {
                document.getElementById(thenPressId(this.chr)).className = "finger" + thenFinger(this.chr.toLowerCase());
            } else {
                document.getElementById(thenPressId(this.chr)).className = "normal";
            }
        } else {
            document.getElementById(thenPressId(this.chr)).className = "normal";
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
    };
}

function thenFinger(t_crka) {
    if (t_crka === ' ') {
        return 5; // Highlight the spacebar.
    } else if (t_crka === 'z' || t_crka === 'a' || t_crka === 'q' || t_crka === '1' || t_crka === '!' || t_crka === '¹' || t_crka === '\\' || t_crka === '~' ||
               t_crka === '/' || t_crka === '?' || t_crka === ';' || t_crka === ':' || t_crka === 'ç' || t_crka === 'p' || t_crka === '0' ||
               t_crka === ')' || t_crka === '\'' || t_crka === '"' || t_crka === '[' || t_crka === '{' || t_crka === 'ª' || t_crka === '-' || t_crka === '_' ||
               t_crka === ']' || t_crka === '}' || t_crka === 'º' || t_crka === '=' || t_crka === '+' || t_crka === '§' || t_crka === '\\' || t_crka === '|' || t_crka === '´' ||
               t_crka === '`') {
        return 4; // Highlight the correct key above in red.
    } else if (t_crka === 'x' || t_crka === 's' || t_crka === 'w' || t_crka === '2' || t_crka === '@' || t_crka === '²' ||
               t_crka === '.' || t_crka === '>' || t_crka === 'l' || t_crka === 'o' || t_crka === '9' || t_crka === '(') {
        return 3; // Highlight the correct key above in green.
    } else if (t_crka === 'c' ||t_crka === '₢' || t_crka === 'd' || t_crka === 'e' || t_crka === '°' || t_crka === '3' || t_crka === '#' || t_crka === '³' ||
               t_crka === ',' || t_crka === '<' || t_crka === 'k' || t_crka === 'i' || t_crka === '8' || t_crka === '*') {
        return 2; // Highlight the correct key above in yellow.
    } else if (t_crka === 'v' || t_crka === 'f' || t_crka === 'r' || t_crka === '4' || t_crka === '$' || t_crka === '£' ||
               t_crka === 'b' || t_crka === 'g' || t_crka === 't' || t_crka === '5' || t_crka === '%' || t_crka === '¢' ||
               t_crka === 'm' || t_crka === 'j' || t_crka === 'u' || t_crka === '7' || t_crka === '&' ||
               t_crka === 'n' || t_crka === 'h' || t_crka === 'y' || t_crka === '6' || t_crka === '¨' || t_crka === '¬') {
        return 1; // Highlight the correct key above in blue.
    } else {
        return 6; // Do not change any highlight.
    }
}

function thenPressId(t_crka) {
    if (t_crka === ' ') {
        return "jkeyspace";
    } else if (t_crka === '\n') {
        return "jkeyenter";
    } else if (t_crka === "\'" || t_crka === '"') {
        return "jkeycrtica";
    } else if (t_crka === '!' || t_crka === '¹') {
        return "jkey1";
    } else if (t_crka === '@' || t_crka === '²') {
        return "jkey2";
    } else if (t_crka === '#' || t_crka === '³') {
        return "jkey3";
    } else if (t_crka === '$' || t_crka === '£') {
        return "jkey4";
    } else if (t_crka === '%' || t_crka === '¢') {
        return "jkey5";
    } else if (t_crka === '¨' || t_crka === '¬') {
        return "jkey6";
    } else if (t_crka === '&') {
        return "jkey7";
    } else if (t_crka === '*') {
        return "jkey8";
    } else if (t_crka === '(') {
        return "jkey9";
    } else if (t_crka === ')') {
        return "jkey0";
    } else if (t_crka === '-' || t_crka === '_') {
        return "jkeyminus";
    } else if (t_crka === '=' || t_crka === '+' || t_crka === '§') {
        return "jkeyequals";
    } else if (t_crka === '/') {
        return "jkeyq";
    } else if (t_crka === '°') {
        return "jkeye";
    } else if (t_crka === '´' || t_crka === '`') {
        return "jkeyacuteaccent";
    } else if (t_crka === '[' || t_crka === '{' || t_crka === 'ª') {
        return "jkeybracketl";
    } else if (t_crka === '~' || t_crka === '^') {
        return "jkeytilde";
    } else if (t_crka === ']' || t_crka === '}' || t_crka === 'º') {
        return "jkeybracketr";
    } else if (t_crka === ';' || t_crka === ':') {
        return "jkeysemicolon";
    } else if (t_crka === "\\" || t_crka === '|') {
        return "jkeybackslash";
    } else if (t_crka === ',' || t_crka === '<') {
        return "jkeycomma";
    } else if (t_crka === '.' || t_crka === '>') {
        return "jkeyperiod";
    } else if (t_crka === '?' || t_crka === '/') {
        return "jkeyslash";
    } else if (t_crka === '₢') {
        return "jkeyc";
    } else {
        return "jkey" + t_crka;
    }
}

function isLetter(str) {
    return str.length === 1 && str.match(/[�a-zç]/i);
}
