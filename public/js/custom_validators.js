/*
 * カスタムValidators
*/
$.extend($.validator.messages, {
	required: '必須項目です',
	remote: '入力値を直してください',
	email: 'メールアドレスを入力してください',
	url: 'URLを入力してください',
	dateISO: '日付が不正です (ISO).',
	dateDE: 'Bitte geben Sie ein gültiges Datum ein.',
	number: '数字を入力してください',
	numberDE: 'Bitte geben Sie eine Nummer ein.',
	digits: '整数を入力してください',
	creditcard: 'クレジットカード番号を入力してください',
	equalTo: '同じ値を入力してください',
	accept: '拡張子付きのデータ(値)を入力してください',
	maxlength: $.validator.format('{0} 文字以下を入力してください'),
	minlength: $.validator.format('{0} 文字以上を入力してください'),
	rangelength: $.validator.format('{0} 文字以上 {1} 文字以下で入力してください'),
	range: $.validator.format(' {0} から {1} までの値を入力してください'),
	max: $.validator.format('{0} 以下の値を入力してください'),
	min: $.validator.format('{0} 以上の値を入力してください')
});

$.validator.addMethod('yyyymm', function(value, element, param) {
	if(value == '') return true;
	if(value.match(/^(\d{4})(\d{2})$/)) {
		var mon = parseInt(RegExp.$2.replace(/^0/, ''));
		return (mon > 0 && mon < 13);
	}
	return false;
}, 'YYYYMM 形式で入力してください');


$.validator.addMethod('regex', function(value, element, param) {
	var regex = param;
	if(typeof(param) == 'string') {
		regex = new RegExp(param);
	}
	return (regex.test(value)) ? true : false;
}, '入力値が正しくありません');
