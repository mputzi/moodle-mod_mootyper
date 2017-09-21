function isCombined(chr) {
    return false;
}

function keyupCombined(e) {
    return false;
}

function keyupFirst(event) {
    return false;
}
THE_LAYOUT = 'Slovenian(V2.1)';
function keyboardElement(ltr) {
    this.chr = ltr.toLowerCase();
    this.alt = false;
    if (isLetter(ltr)) {
        this.shift = ltr.toUpperCase() === ltr;
    } else if (ltr === 'Đ' || ltr === 'Ć' || ltr === 'Č' || ltr === 'Š' || ltr === 'Ž') {
        this.shift = true;
    } else if (ltr === '\\' || ltr === '|' || ltr === '€' || ltr === '÷' || ltr === '×' || ltr === '[' ||
               ltr === ']' || ltr === 'ł' || ltr === 'Ł' || ltr === 'ß' || ltr === '¤' || ltr === '@' ||
               ltr === '{' || ltr === '}' || ltr === '§') {
        this.shift = false;
        this.alt = true;
    } else {
        if (ltr === '!' || ltr === '"' || ltr === '#' || ltr === '$' || ltr === '%' || ltr === '&' ||
           ltr === '/' || ltr === '(' || ltr === ')' || ltr === '=' || ltr === '?' || ltr === '*' ||
           ltr === ':' || ltr === ';' || ltr === '>' || ltr === '_') {
            this.shift = true;
        } else {
            this.shift = false;
        }
    }
    this.turnOn = function () {
        if (this.chr === ' ') {
            document.getElementById(dobiTipkoId(this.chr)).className = "nextSpace";
        } else {
            document.getElementById(dobiTipkoId(this.chr)).className = "next" + dobiFinger(this.chr.toLowerCase());
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
        if (this.chr === 'a' || this.chr === 's' || this.chr === 'd' || this.chr === 'f' || this.chr === 'j' || this.chr === 'k' || this.chr === 'l' || this.chr === 'č') {
            document.getElementById(dobiTipkoId(this.chr)).className = "finger" + dobiFinger(this.chr.toLowerCase());
        } else {
            document.getElementById(dobiTipkoId(this.chr)).className = "normal";
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

function dobiFinger(t_crka) {
    if (t_crka === ' ') {
        return 5; // Highlight the spacebar.
    } else if (t_crka === '\n' || t_crka === '¸' || t_crka === '¨' || t_crka === '1' || t_crka === '!' || t_crka === '~' ||
               t_crka === 'q' || t_crka === '\\' || t_crka === 'a' || t_crka === '>' || t_crka === '<' ||
               t_crka === '0' || t_crka === '=' || t_crka === '˝' || t_crka === 'p' || t_crka === 'č' || t_crka === '.' || t_crka === ':' ||
               t_crka === '\'' || t_crka === '?' || t_crka === 'š' || t_crka === '÷' || t_crka === 'ć' || t_crka === 'ß' || t_crka === '-' ||
               t_crka === '_' || t_crka === '+' || t_crka === '*' || t_crka === '¸' || t_crka === 'đ' || t_crka === '×' ||
               t_crka === 'ž' || t_crka === '¤') {
        return 4; // Highlight the correct key above in red.
    } else if (t_crka === '2' || t_crka === '"' || t_crka === 'ˇ' || t_crka === 'w' || t_crka === '|' || t_crka === 's' || t_crka === 'y' ||
               t_crka === '9' || t_crka === ')' || t_crka === '´' || t_crka === 'o' || t_crka === 'l' || t_crka === 'Ł' || t_crka === ',' || t_crka === ';') {
        return 3; // Highlight the correct key above in green.
    } else if (t_crka === '3' || t_crka === '#' || t_crka === '^' || t_crka === 'e' || t_crka === '€' || t_crka === 'd' || t_crka === 'x' ||
               t_crka === '8' || t_crka === '(' || t_crka === '˙' || t_crka === 'i' || t_crka === 'k' || t_crka === 'ł' ||
               t_crka === 'm' || t_crka === '§') {
        return 2; // Highlight the correct key above in yellow.
    } else if (t_crka === '4' || t_crka === '$' || t_crka === '˘' || t_crka === 'r' || t_crka === 'f' || t_crka === '[' || t_crka === 'c' ||
               t_crka === '5' || t_crka === '%' || t_crka === '°' || t_crka === 't' || t_crka === 'g' || t_crka === ']' || t_crka === 'v' || t_crka === '@' ||
               t_crka === '6' || t_crka === '&' || t_crka === '˛' || t_crka === 'z' || t_crka === 'h' || t_crka === 'b' || t_crka === '{' ||
               t_crka === '7' || t_crka === '/' || t_crka === '`' || t_crka === 'u' || t_crka === 'j' || t_crka === 'n' || t_crka === '}') {
        return 1; // Highlight the correct key above in blue.
    } else {
        return 6; // Do not change any highlight.
    }
}

function dobiTipkoId(t_crka) {
    if (t_crka === ' ') {
        return "jkeyspace";
    } else if (t_crka === ',' || t_crka === ';') {
        return "jkeycomma";
    } else if (t_crka === '\n') {
        return "jkeyenter";
    } else if (t_crka === '.' || t_crka === ':') {
        return "jkeypika";
    } else if (t_crka === '-' || t_crka === '_') {
        return "jkeypomislaj";
    } else if (t_crka === '!') {
        return "jkey1";
    } else if (t_crka === '"') {
        return "jkey2";
    } else if (t_crka === '#') {
        return "jkey3";
    } else if (t_crka === '$') {
        return "jkey4";
    } else if (t_crka === '%') {
        return "jkey5";
    } else if (t_crka === '&') {
        return "jkey6";
    } else if (t_crka === '/') {
        return "jkey7";
    } else if (t_crka === '(') {
        return "jkey8";
    } else if (t_crka === ')') {
        return "jkey9";
    } else if (t_crka === '=') {
        return "jkey0";
    } else if (t_crka === '\\') {
        return "jkeyq";
    } else if (t_crka === '|') {
        return "jkeyw";
    } else if (t_crka === '€') {
        return "jkeye";
    } else if (t_crka === '÷') {
        return "jkeyš";
    } else if (t_crka === '×') {
        return "jkeyđ";
    } else if (t_crka === '[') {
        return "jkeyf";
    } else if (t_crka === ']') {
        return "jkeyg";
    } else if (t_crka.match(/ł/)) {
        return "jkeyk";
    } else if (t_crka.match(/Ł/)) {
        return "jkeyl";
    } else if (t_crka === 'ß') {
        return "jkeyć";
    } else if (t_crka === '¤') {
        return "jkeyž";
    } else if (t_crka === '@') {
        return "jkeyv";
    } else if (t_crka === '{') {
        return "jkeyb";
    } else if (t_crka === '}') {
        return "jkeyn";
    } else if (t_crka === '§') {
        return "jkeym";
    } else if (t_crka === '?' || t_crka === '\'') {
        return "jkeyapotrophe";
    } else if (t_crka === '*' || t_crka === '+') {
        return "jkeyplus";
    } else if (t_crka === '<' || t_crka === '>') {
        return "jkeyckck";
    } else if (this.chr === '\n' || this.chr === '\r\n' || this.chr === '\n\r' || this.chr === '\r') {
        return "jkeyenter";
    } else {
        return "jkey" + t_crka;
    }
}

function isLetter(str) {
    return str.length === 1 && str.match(/[a-zčšžđć]/i);
}
