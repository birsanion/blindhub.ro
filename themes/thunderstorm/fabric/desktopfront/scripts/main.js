/**
 * [PART OF QUICK WEB FRAME]
 * MAIN JAVASCRIPT FILE
 * theme / desktopfront / fabric / scripts / main.js
 */

// when document has finished completely loading, do some things


$.validator.messages.required = 'Acest câmp este obligatoriu'
$.validator.messages.email = 'Vă rugăm să introduceți o adresă de email validă'

$(document).ready(function() {

    $('#hButtonSitewideTextBigger').click(function(kEvent){
    	kEvent.preventDefault();

    	if ($('body').hasClass('size-1')){
    		$('body').removeClass('size-1');
    		$('body').addClass('size-2');
    	}else if ($('body').hasClass('size-2')){
    		$('body').removeClass('size-2');
    		$('body').addClass('size-3');
    	}else if ($('body').hasClass('size-3')){
    		$('body').removeClass('size-3');
    		$('body').addClass('size-4');
    	}else if ($('body').hasClass('size-4')){
    		$('body').removeClass('size-4');
    		$('body').addClass('size-5');
    	}
    });

    $('#hButtonSitewideTextSmaller').click(function(kEvent){
    	kEvent.preventDefault();

    	if ($('body').hasClass('size-5')){
    		$('body').removeClass('size-5');
    		$('body').addClass('size-4');
    	}else if ($('body').hasClass('size-4')){
    		$('body').removeClass('size-4');
    		$('body').addClass('size-3');
    	}else if ($('body').hasClass('size-3')){
    		$('body').removeClass('size-3');
    		$('body').addClass('size-2');
    	}else if ($('body').hasClass('size-2')){
    		$('body').removeClass('size-2');
    		$('body').addClass('size-1');
    	}
    });

    $('#hButtonSitewideHighContrast').click(function(kEvent){
    	kEvent.preventDefault();

    	if ($('body').hasClass('highcontrast')){
    		$('body').removeClass('highcontrast');
    	}else{
    		$('body').addClass('highcontrast');
    	}
    });

    $('.mobile-nav-toggle').click(function (e) {
      $('#navbar').toggleClass('navbar-mobile')
      $(this).toggleClass('bi-list')
      $(this).toggleClass('bi-x')
    })

    $('.navbar .dropdown > a').click(function (e) {
      if ($('#navbar').hasClass('navbar-mobile')) {
        e.preventDefault()
        $(this).next().toggleClass('dropdown-active')
      }
    })
});

// other useful functions and stuff
function htmlentities(pcString)
{
    var pcStr=pcString;
    if (pcString==undefined) pcStr='';

    pcStr=pcStr.replace(/\x26/g,'&amp;');
    pcStr=pcStr.replace(/\x22/g,'&quot;');
    pcStr=pcStr.replace(/\x27/g,'&apos;');
    pcStr=pcStr.replace(/\x3C/g,'&lt;');
    pcStr=pcStr.replace(/\x3E/g,'&gt;');
    pcStr=pcStr.replace(/\xA0/g,'&nbsp;');
    pcStr=pcStr.replace(/\xA1/g,'&iexcl;');
    pcStr=pcStr.replace(/\xA2/g,'&cent;');
    pcStr=pcStr.replace(/\xA3/g,'&pound;');
    pcStr=pcStr.replace(/\xA4/g,'&curren;');
    pcStr=pcStr.replace(/\xA5/g,'&yen;');
    pcStr=pcStr.replace(/\xA6/g,'&brvbar;');
    pcStr=pcStr.replace(/\xA7/g,'&sect;');
    pcStr=pcStr.replace(/\xA8/g,'&uml;');
    pcStr=pcStr.replace(/\xA9/g,'&copy;');
    pcStr=pcStr.replace(/\xAA/g,'&ordf;');
    pcStr=pcStr.replace(/\xAB/g,'&laquo;');
    pcStr=pcStr.replace(/\xAC/g,'&not;');
    pcStr=pcStr.replace(/\xAD/g,'&shy;');
    pcStr=pcStr.replace(/\xAE/g,'&reg;');
    pcStr=pcStr.replace(/\xAF/g,'&macr;');
    pcStr=pcStr.replace(/\xB0/g,'&deg;');
    pcStr=pcStr.replace(/\xB1/g,'&plusmn;');
    pcStr=pcStr.replace(/\xB2/g,'&sup2;');
    pcStr=pcStr.replace(/\xB3/g,'&sup3;');
    pcStr=pcStr.replace(/\xB4/g,'&acute;');
    pcStr=pcStr.replace(/\xB5/g,'&micro;');
    pcStr=pcStr.replace(/\xB6/g,'&para;');
    pcStr=pcStr.replace(/\xB7/g,'&middot;');
    pcStr=pcStr.replace(/\xB8/g,'&cedil;');
    pcStr=pcStr.replace(/\xB9/g,'&sup1;');
    pcStr=pcStr.replace(/\xBA/g,'&ordm;');
    pcStr=pcStr.replace(/\xBB/g,'&raquo;');
    pcStr=pcStr.replace(/\xBC/g,'&frac14;');
    pcStr=pcStr.replace(/\xBD/g,'&frac12;');
    pcStr=pcStr.replace(/\xBE/g,'&frac34;');
    pcStr=pcStr.replace(/\xBF/g,'&iquest;');
    pcStr=pcStr.replace(/\xD7/g,'&times;');
    pcStr=pcStr.replace(/\xF7/g,'&divide;');
    pcStr=pcStr.replace(/\xC0/g,'&Agrave;');
    pcStr=pcStr.replace(/\xC1/g,'&Aacute;');
    pcStr=pcStr.replace(/\xC2/g,'&Acirc;');
    pcStr=pcStr.replace(/\xC3/g,'&Atilde;');
    pcStr=pcStr.replace(/\xC4/g,'&Auml;');
    pcStr=pcStr.replace(/\xC5/g,'&Aring;');
    pcStr=pcStr.replace(/\xC6/g,'&AElig;');
    pcStr=pcStr.replace(/\xC7/g,'&Ccedil;');
    pcStr=pcStr.replace(/\xC8/g,'&Egrave;');
    pcStr=pcStr.replace(/\xC9/g,'&Eacute;');
    pcStr=pcStr.replace(/\xCA/g,'&Ecirc;');
    pcStr=pcStr.replace(/\xCB/g,'&Euml;');
    pcStr=pcStr.replace(/\xCC/g,'&Igrave;');
    pcStr=pcStr.replace(/\xCD/g,'&Iacute;');
    pcStr=pcStr.replace(/\xCE/g,'&Icirc;');
    pcStr=pcStr.replace(/\xCF/g,'&Iuml;');
    pcStr=pcStr.replace(/\xD0/g,'&ETH;');
    pcStr=pcStr.replace(/\xD1/g,'&Ntilde;');
    pcStr=pcStr.replace(/\xD2/g,'&Ograve;');
    pcStr=pcStr.replace(/\xD3/g,'&Oacute;');
    pcStr=pcStr.replace(/\xD4/g,'&Ocirc;');
    pcStr=pcStr.replace(/\xD5/g,'&Otilde;');
    pcStr=pcStr.replace(/\xD6/g,'&Ouml;');
    pcStr=pcStr.replace(/\xD8/g,'&Oslash;');
    pcStr=pcStr.replace(/\xD9/g,'&Ugrave;');
    pcStr=pcStr.replace(/\xDA/g,'&Uacute;');
    pcStr=pcStr.replace(/\xDB/g,'&Ucirc;');
    pcStr=pcStr.replace(/\xDC/g,'&Uuml;');
    pcStr=pcStr.replace(/\xDD/g,'&Yacute;');
    pcStr=pcStr.replace(/\xDE/g,'&THORN;');
    pcStr=pcStr.replace(/\xDF/g,'&szlig;');
    pcStr=pcStr.replace(/\xE0/g,'&agrave;');
    pcStr=pcStr.replace(/\xE1/g,'&aacute;');
    pcStr=pcStr.replace(/\xE2/g,'&acirc;');
    pcStr=pcStr.replace(/\xE3/g,'&atilde;');
    pcStr=pcStr.replace(/\xE4/g,'&auml;');
    pcStr=pcStr.replace(/\xE5/g,'&aring;');
    pcStr=pcStr.replace(/\xE6/g,'&aelig;');
    pcStr=pcStr.replace(/\xE7/g,'&ccedil;');
    pcStr=pcStr.replace(/\xE8/g,'&egrave;');
    pcStr=pcStr.replace(/\xE9/g,'&eacute;');
    pcStr=pcStr.replace(/\xEA/g,'&ecirc;');
    pcStr=pcStr.replace(/\xEB/g,'&euml;');
    pcStr=pcStr.replace(/\xEC/g,'&igrave;');
    pcStr=pcStr.replace(/\xED/g,'&iacute;');
    pcStr=pcStr.replace(/\xEE/g,'&icirc;');
    pcStr=pcStr.replace(/\xEF/g,'&iuml;');
    pcStr=pcStr.replace(/\xF0/g,'&eth;');
    pcStr=pcStr.replace(/\xF1/g,'&ntilde;');
    pcStr=pcStr.replace(/\xF2/g,'&ograve;');
    pcStr=pcStr.replace(/\xF3/g,'&oacute;');
    pcStr=pcStr.replace(/\xF4/g,'&ocirc;');
    pcStr=pcStr.replace(/\xF5/g,'&otilde;');
    pcStr=pcStr.replace(/\xF6/g,'&ouml;');
    pcStr=pcStr.replace(/\xF8/g,'&oslash;');
    pcStr=pcStr.replace(/\xF9/g,'&ugrave;');
    pcStr=pcStr.replace(/\xFA/g,'&uacute;');
    pcStr=pcStr.replace(/\xFB/g,'&ucirc;');
    pcStr=pcStr.replace(/\xFC/g,'&uuml;');
    pcStr=pcStr.replace(/\xFD/g,'&yacute;');
    pcStr=pcStr.replace(/\xFE/g,'&thorn;');
    pcStr=pcStr.replace(/\xFF/g,'&yuml;');

    return pcStr;
}

function htmlspecialchars(pcString)
{
    var pcStr=pcString;
    if (pcString==undefined) pcStr='';

    pcStr=pcStr.replace(/\x26/g,'&amp;');
    pcStr=pcStr.replace(/\x22/g,'&quot;');
    pcStr=pcStr.replace(/\x27/g,'&apos;');
    pcStr=pcStr.replace(/\x3C/g,'&lt;');
    pcStr=pcStr.replace(/\x3E/g,'&gt;');

    return pcStr;
}

function htmlentities_frombrowser(pcString)
{
    var pcStr=pcString;
    if (pcString==undefined) pcStr='';

    pcStr=pcStr.replace(/\x22/g,'&quot;');
    pcStr=pcStr.replace(/\x27/g,'&apos;');
    pcStr=pcStr.replace(/\xC2/g,'&Acirc;');
    pcStr=pcStr.replace(/\xCA/g,'&Ecirc;');
    pcStr=pcStr.replace(/\xCE/g,'&Icirc;');
    pcStr=pcStr.replace(/\xD4/g,'&Ocirc;');
    pcStr=pcStr.replace(/\xDB/g,'&Ucirc;');
    pcStr=pcStr.replace(/\xE2/g,'&acirc;');
    pcStr=pcStr.replace(/\xEA/g,'&ecirc;');
    pcStr=pcStr.replace(/\xEE/g,'&icirc;');
    pcStr=pcStr.replace(/\xF4/g,'&ocirc;');
    pcStr=pcStr.replace(/\xFB/g,'&ucirc;');

    return pcStr;
}

function sprintf()
{
  // http://kevin.vanzonneveld.net
  // + original by: Ash Searle (http://hexmen.com/blog/)
  // + namespaced by: Michael White (http://getsprink.com)
  // + tweaked by: Jack
  // + improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // + input by: Paulo Freitas
  // + improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // + input by: Brett Zamir (http://brett-zamir.me)
  // + improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // + improved by: Dj
  // + improved by: Allidylls
  // * example 1: sprintf("%01.2f", 123.1);
  // * returns 1: 123.10
  // * example 2: sprintf("[%10s]", 'monkey');
  // * returns 2: '[ monkey]'
  // * example 3: sprintf("[%'#10s]", 'monkey');
  // * returns 3: '[####monkey]'
  // * example 4: sprintf("%d", 123456789012345);
  // * returns 4: '123456789012345'

    // sprintf() is ...
    // Copyright (c) 2013 Kevin van Zonneveld (http://kvz.io)
    // and Contributors (http://phpjs.org/authors)
    //
    // Permission is hereby granted, free of charge, to any person obtaining a copy of
    // this software and associated documentation files (the "Software"), to deal in
    // the Software without restriction, including without limitation the rights to
    // use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies
    // of the Software, and to permit persons to whom the Software is furnished to do
    // so, subject to the following conditions:
    //
    // The above copyright notice and this permission notice shall be included in all
    // copies or substantial portions of the Software.
    //
    // THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
    // IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
    // FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
    // AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
    // LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
    // OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
    // SOFTWARE.

  var regex = /%%|%(\d+\$)?([-+\'#0 ]*)(\*\d+\$|\*|\d+)?(\.(\*\d+\$|\*|\d+))?([scboxXuideEfFgG])/g;
  var a = arguments,
    i = 0,
    format = a[i++];

  // pad()
  var pad = function (str, len, chr, leftJustify) {
    if (!chr) {
      chr = ' ';
    }
    var padding = (str.length >= len) ? '' : Array(1 + len - str.length >>> 0).join(chr);
    return leftJustify ? str + padding : padding + str;
  };

  // justify()
  var justify = function (value, prefix, leftJustify, minWidth, zeroPad, customPadChar) {
    var diff = minWidth - value.length;
    if (diff > 0) {
      if (leftJustify || !zeroPad) {
        value = pad(value, minWidth, customPadChar, leftJustify);
      } else {
        value = value.slice(0, prefix.length) + pad('', diff, '0', true) + value.slice(prefix.length);
      }
    }
    return value;
  };

  // formatBaseX()
  var formatBaseX = function (value, base, prefix, leftJustify, minWidth, precision, zeroPad) {
    // Note: casts negative numbers to positive ones
    var number = value >>> 0;
    prefix = prefix && number && {
      '2': '0b',
      '8': '0',
      '16': '0x'
    }[base] || '';
    value = prefix + pad(number.toString(base), precision || 0, '0', false);
    return justify(value, prefix, leftJustify, minWidth, zeroPad);
  };

  // formatString()
  var formatString = function (value, leftJustify, minWidth, precision, zeroPad, customPadChar) {
    if (precision != null) {
      value = value.slice(0, precision);
    }
    return justify(value, '', leftJustify, minWidth, zeroPad, customPadChar);
  };

  // doFormat()
  var doFormat = function (substring, valueIndex, flags, minWidth, _, precision, type) {
    var number;
    var prefix;
    var method;
    var textTransform;
    var value;

    if (substring === '%%') {
      return '%';
    }

    // parse flags
    var leftJustify = false,
      positivePrefix = '',
      zeroPad = false,
      prefixBaseX = false,
      customPadChar = ' ';
    var flagsl = flags.length;
    for (var j = 0; flags && j < flagsl; j++) {
      switch (flags.charAt(j)) {
      case ' ':
        positivePrefix = ' ';
        break;
      case '+':
        positivePrefix = '+';
        break;
      case '-':
        leftJustify = true;
        break;
      case "'":
        customPadChar = flags.charAt(j + 1);
        break;
      case '0':
        zeroPad = true;
        break;
      case '#':
        prefixBaseX = true;
        break;
      }
    }

    // parameters may be null, undefined, empty-string or real valued
    // we want to ignore null, undefined and empty-string values
    if (!minWidth) {
      minWidth = 0;
    } else if (minWidth === '*') {
      minWidth = +a[i++];
    } else if (minWidth.charAt(0) == '*') {
      minWidth = +a[minWidth.slice(1, -1)];
    } else {
      minWidth = +minWidth;
    }

    // Note: undocumented perl feature:
    if (minWidth < 0) {
      minWidth = -minWidth;
      leftJustify = true;
    }

    if (!isFinite(minWidth)) {
      throw new Error('sprintf: (minimum-)width must be finite');
    }

    if (!precision) {
      precision = 'fFeE'.indexOf(type) > -1 ? 6 : (type === 'd') ? 0 : undefined;
    } else if (precision === '*') {
      precision = +a[i++];
    } else if (precision.charAt(0) == '*') {
      precision = +a[precision.slice(1, -1)];
    } else {
      precision = +precision;
    }

    // grab value using valueIndex if required?
    value = valueIndex ? a[valueIndex.slice(0, -1)] : a[i++];

    switch (type) {
    case 's':
      return formatString(String(value), leftJustify, minWidth, precision, zeroPad, customPadChar);
    case 'c':
      return formatString(String.fromCharCode(+value), leftJustify, minWidth, precision, zeroPad);
    case 'b':
      return formatBaseX(value, 2, prefixBaseX, leftJustify, minWidth, precision, zeroPad);
    case 'o':
      return formatBaseX(value, 8, prefixBaseX, leftJustify, minWidth, precision, zeroPad);
    case 'x':
      return formatBaseX(value, 16, prefixBaseX, leftJustify, minWidth, precision, zeroPad);
    case 'X':
      return formatBaseX(value, 16, prefixBaseX, leftJustify, minWidth, precision, zeroPad).toUpperCase();
    case 'u':
      return formatBaseX(value, 10, prefixBaseX, leftJustify, minWidth, precision, zeroPad);
    case 'i':
    case 'd':
      number = +value || 0;
      number = Math.round(number - number % 1); // Plain Math.round doesn't just truncate
      prefix = number < 0 ? '-' : positivePrefix;
      value = prefix + pad(String(Math.abs(number)), precision, '0', false);
      return justify(value, prefix, leftJustify, minWidth, zeroPad);
    case 'e':
    case 'E':
    case 'f': // Should handle locales (as per setlocale)
    case 'F':
    case 'g':
    case 'G':
      number = +value;
      prefix = number < 0 ? '-' : positivePrefix;
      method = ['toExponential', 'toFixed', 'toPrecision']['efg'.indexOf(type.toLowerCase())];
      textTransform = ['toString', 'toUpperCase']['eEfFgG'.indexOf(type) % 2];
      value = prefix + Math.abs(number)[method](precision);
      return justify(value, prefix, leftJustify, minWidth, zeroPad)[textTransform]();
    default:
      return substring;
    }
  };

  return format.replace(regex, doFormat);
}

function strcount(pcHaystack, pcNeedle)
{
    var nCnt=0;
    var nLen=pcHaystack.length;
    for (var i=0; i<nLen; i++) if (pcHaystack[i]==pcNeedle[0]) nCnt++;
    return nCnt;
}
