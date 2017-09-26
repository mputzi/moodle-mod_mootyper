function isCombined(chr) {
    return false;
}

function keyupCombined(e) {
    return false;
}

function keyupFirst(event) {
    return false;
}
THE_LAYOUT = 'Slovenian(V3)';
function keyboardElement(ltr) {
    if (ltr !== 'Ł') {
        this.chr = ltr.toLowerCase();
    } else {
        this.chr = ltr.toUpperCase();
    }
    this.alt = false;
    if (isLetter(ltr)) {
        this.shift = ltr.toUpperCase() === ltr;
    } else if (ltr.match(/[ĐĆČŠŽ]/)) {
        this.shift = true;
    } else if (ltr.match(/[\\|€÷×\[\]łŁß¤@{}§]/)) {
        this.shift = false;
        this.alt = true;
    } else {
        // @codingStandardsIgnoreLine
        if (ltr.match(/[¨!"#$%&/()=?*>;:_]/i)) {
            this.shift = true;
        } else {
            this.shift = false;
        }
    }
    this.turnOn = function () {
        if (this.chr === ' ') {
            document.getElementById(getKeyID(this.chr)).className = "nextSpace";
        } else {
            document.getElementById(getKeyID(this.chr)).className = "next" + thenFinger(this.chr.toLowerCase());
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
        // @codingStandardsIgnoreLine
            if (this.chr.match(/[asdfjklč]/i)) {
            document.getElementById(getKeyID(this.chr)).className = "finger" + thenFinger(this.chr.toLowerCase());
        } else {
            document.getElementById(getKeyID(this.chr)).className = "normal";
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
    // @codingStandardsIgnoreLine
    } else if (t_crka.match(/[\n¸¨1!~q\\a><0=˝pč.:\'?š÷ćß\-_+*¸đ×ž¤]/i)) {
        return 4; // Highlight the correct key above in red.
    // @codingStandardsIgnoreLine
    } else if (t_crka.match(/[2"ˇw|sy9)´olŁ,;]/)) {
        return 3; // Highlight the correct key above in green.
    // @codingStandardsIgnoreLine
    } else if (t_crka.match(/[3#^e€dx8(˙ikłm§]/)) {
        return 2; // Highlight the correct key above in yellow.
    // @codingStandardsIgnoreLine
    } else if (t_crka.match(/[4$˘rf\[c5%°tg\]v@6&˛zhb{7/`ujn}]/i)) {
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
    } else if (t_crka === '¸' || t_crka === '¨') {
        return "jkeytildo";
    } else if (t_crka === '.' || t_crka === ':') {
        return "jkeyperiod";
    } else if (t_crka === '-' || t_crka === '_') {
        return "jkeyminus";
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
        return "jkeyapostrophe";
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
