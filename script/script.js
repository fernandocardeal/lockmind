const toggleSenha = (img, idCampo) => {

    const input = document.getElementById(idCampo);
    const tipoAtual = input.getAttribute("type");

    if (tipoAtual === "password") {
        input.setAttribute("type", "text");
        img.src = "images/openeye.png";
        img.alt = "Ocultar senha";
    } else {
        input.setAttribute("type", "password");
        img.src = "images/closedeye.png";
        img.alt = "Mostrar senha";
    }
}

const copiarTexto = (id) => {

    const campo = document.getElementById(id);

    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(campo.value)
            .then(() => alert("Texto copiado com sucesso!"))
            .catch(err => alert("Erro ao copiar: " + err));
    } else {
        campo.select();
        document.execCommand("copy");
    }
}