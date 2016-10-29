function validarCampoTexto(objetoNombre, errorNombre, validacionTipo, esObligatorio, validacion) {
	objeto = document.getElementById(objetoNombre);
	objetoError = document.getElementById(errorNombre);

	if (objeto.value == "") {
		if (esObligatorio) {
			objetoError.style.display = "block";
		} else {
			objetoError.style.display = "none";
			return true;
		}
	} else {
		if (validarValor(validacionTipo, objeto.value)) {
			objetoError.style.display = "none";
			return true;
		} else {
			objetoError.style.display = "block";
		}
	}

	if (validacion) {
		objeto.focus();
	}

	return false;
}

function validarAreaTexto(objetoNombre, errorNombre, esObligatorio, validacion) {
	objeto = document.getElementById(objetoNombre);
	objetoError = document.getElementById(errorNombre);

	if (objeto.value == "") {
		if (esObligatorio) {
			objetoError.style.display = "block";
		} else {
			objetoError.style.display = "none";
			return true;
		}
	} else {
		objetoError.style.display = "none";
		return true;
	}
}

function validarValor(validacionTipo, valor) {

	switch (validacionTipo) {
		case "entero": {
			return esEntero(valor);
		} case "enteroPositivoConCero": {
			return esEnteroPositivoConCero(valor);
		} case "enteroPositivoSinCero": {
			return esEnteroPositivoSinCero(valor);
		} case "decimal": {
			return esDecimal(valor);
		} case "decimalPositivoConCero": {
			return esDecimalPositivoConCero(valor);
		} case "decimalPositivoSinCero": {
			return esDecimalPositivoSinCero(valor);
		} case "correo": {
			return esCorreo(valor);
		}  case "clave": {
			return esClave(valor);
		} default: {
			return true;
		}
	}
}

function esClave(texto) {

	if (texto.length < 6) {
		return false;
	} else {
		for (var i = 0; i < texto.length; i++) {
			caracter = texto.charAt(i);
			if(caracter < "0" || (caracter > "9" && caracter < "A") || (caracter > "Z" && caracter < "a") || caracter > "z") {
				return false;
			}
		}
	}

	return true;
}

function esEntero(numero) {

	var caracter = numero.charAt(0);
	if((caracter < "0" || caracter > "9") && caracter != "-") {
		return false;
	}

	for (var i = 1; i < numero.length; i++) {
		caracter = numero.charAt(i);
		if(caracter < "0" || caracter > "9") {
			return false;
		}
	}

	return true;
}

function esEnteroPositivoConCero(numero) {

	for (var i = 0; i < numero.length; i++) {
		var caracter = numero.charAt(i);
		if(caracter < "0" || caracter > "9") {
			return false;
		}
	}

	return true;
}

function esEnteroPositivoSinCero(numero) {

	for (var i = 0; i < numero.length; i++) {
		var caracter = numero.charAt(i);
		if(caracter < "0" || caracter > "9") {
			return false;
		}
	}

	numero = parseInt(numero, 10);
	if (numero == 0) {
		return false;
	}

	return true;
}

function esDecimal(numero) {

	var caracter = numero.charAt(0);
	if((caracter < "0" || caracter > "9") && caracter != "-") {
		return false;
	}

	var punto = false;
	for (var i = 1; i < numero.length; i++) {
		var caracter = numero.charAt(i);
		if((caracter < "0" || caracter > "9")) {
			if (caracter == "." && punto == false) {
				punto = true;
			} else {
				return false;
			}
		}
	}

	return true;
}

function esDecimalPositivoConCero(numero) {

	var punto = false;
	for (var i = 0; i < numero.length; i++) {
		var caracter = numero.charAt(i);
		if(caracter < "0" || caracter > "9") {
			if (caracter == "." && punto == false) {
				punto = true;
			} else {
				return false;
			}
		}
	}

	return true;
}

function esDecimalPositivoSinCero(numero) {

	var punto = false;
	for (var i = 0; i < numero.length; i++) {
		var caracter = numero.charAt(i);
		if(caracter < "0" || caracter > "9") {
			if (caracter == "." && punto == false) {
				punto = true;
			} else {
				return false;
			}
		}
	}

	numero = parseFloat(numero);
	if (numero == 0) {
		return false;
	}

	return true;
}

function esCorreo(correo) {
	if (correo.indexOf(" ") != -1) {
		return false;
	}

	correoSplit = correo.split("@");
	if (correoSplit.length != 2 || correoSplit[0] == "" || correoSplit[1] == "") {
		return false;
	}

	correoSplit = correoSplit[1].split(".");
	if (correoSplit.length > 1) {
		for (var i = 0; i < correoSplit.length; i++) {
			if (correoSplit[i] == "") {
				return false;
			}
		}
	} else {
		return false;
	}

	return true;
}

function validarLista(objetoNombre, errorNombre, esObligatorio, tituloId, validacion) {
	objeto = document.getElementById(objetoNombre);
	objetoError = document.getElementById(errorNombre);

	if (esObligatorio && objeto.value == tituloId) {
		objetoError.style.display = "block";

		if (validacion) {
			objeto.focus();
		}

		return false;
	} else {
		objetoError.style.display = "none";
		return true;
	}
}

function validarListaMultiple(objetoNombre, errorNombre, esObligatorio, validacion) {
	objetoTodos = document.getElementById(objetoNombre + "Todos");
	objeto = document.getElementById(objetoNombre + "[]");
	objetoError = document.getElementById(errorNombre);

	if (esObligatorio && objeto.options.length == 0) {
		objetoError.style.display = "block";

		if (validacion) {
			objeto.focus();
		}

		return false;
	} else {
		for (i = 0; i < objeto.options.length; i++) {
			objeto.options[i].selected = true;
		}

		objetoError.style.display = "none";
		return true;
	}
}

function validarCajaVerificacion(objetoNombre, errorNombre, esObligatorio, validacion) {
	objeto = document.getElementById(objetoNombre);
	objetoArr = document.getElementsByName(objetoNombre);
	objetoError = document.getElementById(errorNombre);

	if (esObligatorio) {
		for (var i = 0; i < objetoArr.length; i++) {
			if (objetoArr[i].checked == true) {
				objetoError.style.display = "none";
				return true;
			}
		}
	} else {
		objetoError.style.display = "none";
		return true;
	}

	objetoError.style.display = "block";

	if (validacion) {
		objeto.focus();
	}

	return false;
}

function validarBotonRadio(objetoNombre, errorNombre, esObligatorio, validacion) {
	objeto = document.getElementById(objetoNombre);
	objetoArr = document.getElementsByName(objetoNombre);
	objetoError = document.getElementById(errorNombre);

	if (esObligatorio) {
		for (var i = 0; i < objetoArr.length; i++) {
			if (objetoArr[i].checked == true) {
				objetoError.style.display = "none";
				return true;
			}
		}
	} else {
		objetoError.style.display = "none";
		return true;
	}

	objetoError.style.display = "block";

	if (validacion) {
		objeto.focus();
	}

	return false;
}

function validarCalendario(objetoNombre, errorNombre, esObligatorio, validacion) {
	objeto = document.getElementById(objetoNombre);
	objetoError = document.getElementById(errorNombre);

	if (esObligatorio && objeto.value == "") {
		objetoError.style.display = "block";

		if (validacion) {
			objeto.focus();
		}

		return false;
	} else {
		objetoError.style.display = "none";
		return true;
	}
}

function validarCampoArchivo(objetoNombre, errorNombre, esObligatorio, validacion) {
	objeto = document.getElementById(objetoNombre);
	objetoError = document.getElementById(errorNombre);

	if (esObligatorio && objeto.value == "") {
		objetoError.style.display = "block";

		if (validacion) {
			objeto.focus();
		}

		return false;
	} else {
		objetoError.style.display = "none";
		return true;
	}
}
