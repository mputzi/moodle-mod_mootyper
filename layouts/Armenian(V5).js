/**
 * @fileOverview Armenian(V5.2) keyboard driver.
 * @author <a href="mailto:drachels@drachels.com">AL Rachels</a>
 * @version 5.2
 * @since 02/18/2019
 */

/**
 * Check for combined character.
 * @param {string} chr The combined character.
 * @returns {string} The character.
 */
function isCombined(chr) {
    return false;
}

/**
 * Process keyup for combined character.
 * @param {string} e The combined character.
 * @returns {bolean} The result.
 */
function keyupCombined(e) {
    return false;
}

/**
 * Process keyupFirst.
 * @param {string} event Type of event.
 * @returns {bolean} The event.
 */
function keyupFirst(event) {
    return false;
}

/**
 * Check for character typed so flags can be set.
 * @param {string} ltr The current letter.
 */
function keyboardElement(ltr) {
    this.chr = ltr.toLowerCase();
    this.shift = false;
    this.alt = false;
    if (isLetter(ltr)) { // Set specified shift key for right or left.
        if (ltr.match(/[՜ԷԹՓՁՋՔՈԵՌՏԱՍԴՖԳԶՂՑՎԲ]/)) {
            this.shiftright = true;
        } else if (ltr.match(/[ՒևՐՉՃ—ԺԸՒԻՕՊԽԾՇՀՅԿԼ։"ՆՄ<>՞]/)) {
            this.shiftleft = true;
        }
    }
    this.turnOn = function() {
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
        if (this.shiftleft) {
            document.getElementById('jkeyshiftl').className = "next4";
        }
        if (this.shiftright) {
            document.getElementById('jkeyshiftr').className = "next4";
        }
        if (this.alt) {
            document.getElementById('jkeyaltgr').className = "nextSpace";
        }
    };
    this.turnOff = function() {
        if (isLetter(this.chr)) {
            // @codingStandardsIgnoreLine
            if (this.chr.match(/[ասդֆյկլ;]/i)) {
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
        if (this.shiftleft) {
            document.getElementById('jkeyshiftl').className = "normal";
        }
        if (this.shiftright) {
            document.getElementById('jkeyshiftr').className = "normal";
        }
        if (this.alt) {
            document.getElementById('jkeyaltgr').className = "normal";
        }
    };
}

/**
 * Set color flag based on current character.
 * @param {string} tCrka The current character.
 * @returns {number}.
 */
function thenFinger(tCrka) {
    if (tCrka === ' ') {
        return 5; // Highlight the spacebar.
        // @codingStandardsIgnoreLine
    } else if (tCrka.match(/[՜՝էքազճպ;։՛"/՞\-—խ՛ժծշ]/i)) {
        return 4; // Highlight the correct key above in red.
        // @codingStandardsIgnoreLine
    } else if (tCrka.match(/[թոսղչօլ․>]/i)) {
        return 3; // Highlight the correct key above in green.
        // @codingStandardsIgnoreLine
    } else if (tCrka.match(/[փեդցրիկ,<]/i)) {
        return 2; // Highlight the correct key above in yellow.
        // @codingStandardsIgnoreLine
    } else if (tCrka.match(/[ձռֆվջտգբւըհնևւյմ]/i)) {
        return 1; // Highlight the correct key above in blue.
    } else {
        return 6; // Do not change any highlight.
    }
}

/**
 * Get ID of key to highlight based on current character.
 * @param {string} tCrka The current character.
 * @returns {string}.
 */
function getKeyID(tCrka) {
    if (tCrka === ' ') {
        return "jkeyspace";
    } else if (tCrka === '\n') {
        return "jkeyenter";
    } else if (tCrka === "՝" || tCrka === '՜') {
        return "jkey՝";
    } else if (tCrka === 'է') {
        return "jkey1";
    } else if (tCrka === 'թ') {
        return "jkey2";
    } else if (tCrka === 'փ') {
        return "jkey3";
    } else if (tCrka === 'ձ') {
        return "jkey4";
    } else if (tCrka === 'ջ') {
        return "jkey5";
    } else if (tCrka === 'և') {
        return "jkey7";
    } else if (tCrka === 'ր') {
        return "jkey8";
    } else if (tCrka === 'չ') {
        return "jkey9";
    } else if (tCrka === 'ճ') {
        return "jkey0";
    } else if (tCrka === '-' || tCrka === '—') {
        return "jkey-";
    } else if (tCrka === 'ժ') {
        return "jkeyequals";
    } else if (tCrka === 'խ') {
        return "jkeyխ";
    } else if (tCrka === 'ծ') {
        return "jkeyծ";
    } else if (tCrka === 'շ') {
        return "jkeyշ";
    } else if (tCrka === ';' || tCrka === '։') {
        return "jkeysemicolon";
    } else if (tCrka === "՛" || tCrka === '"') {
        return "jkey՛";
    } else if (tCrka === ',' || tCrka === '<') {
        return "jkeycomma";
    } else if (tCrka === '․' || tCrka === '>') {
        return "jkeyperiod";
    } else if (tCrka === '՞' || tCrka === '/') {
        return "jkeyslash";
    } else {
        return "jkey" + tCrka;
    }
}

/**
 * Is the typed letter part of the current alphabet.
 * @param {string} str The current letter.
 * @returns {(number|Array)}.
 */
function isLetter(str) {
    return str.length === 1 && str.match(/[!-ﻼ]/i);
}
