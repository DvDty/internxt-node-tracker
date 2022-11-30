// https://flaviocopes.com/rgb-color-codes/
let colors = [
    '128,0,0',
    '0,100,0',
    '153,50,204',
    '32,178,170',
    '139,0,0',
    '0,128,0',
    '138,43,226',
    '30,144,255',
    '165,42,42',
    '34,139,34',
    '210,105,30',
    '65,105,225',
    '178,34,34',
    '50,205,50',
    '106,90,205',
    '220,20,60',
    '0,255,127',
    '135,206,23',
];

let getNextColor = function nextColor() {
    let index = 0;

    return function() {
        index = index > colors.length - 1 ? 0 : index;
        return 'rgb(' + colors[index++] + ')';
    };
}();
