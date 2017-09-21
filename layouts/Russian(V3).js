function isCombined(chr) {
    return false;
}

function keyupCombined(e) {
    return false;
}

function keyupFirst(event) {
    return false;
}

THE_LAYOUT = 'Russian(V3)';

function keyboardElement(ltr) {
    this.chr = ltr.toLowerCase();
    this.alt = false;
    if (isLetter(ltr)) {
        this.shift = ltr.toUpperCase() === ltr;
    } else {
        // @codingStandardsIgnoreLine
        if (ltr.match(/[!"№;%:?*()_+/,]/i)) {
            this.shift = true;
        } else {
            this.shift = false;
        }
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
        if ( this.alt) {
            document.getElementById('jkeyaltgr').className = "nextSpace";
        }
    };
    this.turnOff = function () {
        if (isLetter(this.chr)) {
            // @codingStandardsIgnoreLine
            if (this.chr.match(/[фываолдж]/i)) {
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
    };
}

function thenFinger(t_crka) {
    if (t_crka === ' ') {
        return 5; // Highlight the spacebar.
		// @codingStandardsIgnoreLine
    } else if (t_crka.match(/[ё1!йфя0\-=\\)_+/зхъжэ.,]/i)) {
        return 4; // Highlight the correct key above in red.
    } else if (t_crka.match(/[2цыч"9щдю(]/i)) {
        return 3; // Highlight the correct key above in green.
    } else if (t_crka.match(/[3увс№8шлб*]/i)) {
        return 2; // Highlight the correct key above in yellow.
    } else if (t_crka.match(/[4кам5епи;%6нрт7гоь:?]/i)) {
        return 1; // Highlight the correct key above in blue.
    } else {
        return 6; // Do not change any highlight.
    }
}

function getKeyID(t_crka) {
    if (t_crka === ' ') {
        return "jkeyspace";
    } else if (t_crka === '\n') {
        return "jkeyenter";
    } else if (t_crka === '!') {
        return "jkey1";
    } else if (t_crka === '"') {
        return "jkey2";
    } else if (t_crka === '№') {
        return "jkey3";
    } else if (t_crka === ';') {
        return "jkey4";
    } else if (t_crka === '%') {
        return "jkey5";
    } else if (t_crka === ':') {
        return "jkey6";
    } else if (t_crka === '?') {
        return "jkey7";
    } else if (t_crka === '*') {
        return "jkey8";
    } else if (t_crka === '(') {
        return "jkey9";
    } else if (t_crka === ')') {
        return "jkey0";
    } else if (t_crka === '-' || t_crka === '_') {
        return "jkeyminus";
    } else if (t_crka === '.' || t_crka === ',') {
        return "jkeyperiod";
    } else if (t_crka === '=' || t_crka === '+') {
        return "jkeyequals";
    } else if (t_crka === "\\" || t_crka === '/') {
        return "jkeybackslash";
    } else {
        return "jkey" + t_crka;
    }
}

function isLetter(str) {
    return str.length === 1 && str.match(/[ёйцукенгшщзхъфывапролджэячсмитьбю]/i);
}
