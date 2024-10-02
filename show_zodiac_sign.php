<?php include('assets/layouts/header.php'); ?>

<?php
$data_nascimento = $_POST['data_nascimento'] ?? null;

if ($data_nascimento) {
    $signos = simplexml_load_file(__DIR__ . "/signos.xml");

    // Formatar a data de nascimento para o formato "dia/mês"
    $nascimento = DateTime::createFromFormat('Y-m-d', $data_nascimento);
    $dia_mes_nasc = $nascimento->format('m-d');  // Usamos "m-d" para comparar apenas o mês e dia

    $signo_encontrado = null;

    foreach ($signos->signo as $signo) {
        // Converte as datas do XML para o formato "m-d" (mês-dia)
        $dataInicio = DateTime::createFromFormat('d/m', (string)$signo->dataInicio)->format('m-d');
        $dataFim = DateTime::createFromFormat('d/m', (string)$signo->dataFim)->format('m-d');

        if ($dataInicio > $dataFim) {
            // Caso onde o signo atravessa o final de um ano e o início do outro (exemplo: Capricórnio)
            if ($dia_mes_nasc >= $dataInicio || $dia_mes_nasc <= $dataFim) {
                $signo_encontrado = $signo;
                break;
            }
        } else {
            // Signos normais que não atravessam o ano
            if ($dia_mes_nasc >= $dataInicio && $dia_mes_nasc <= $dataFim) {
                $signo_encontrado = $signo;
                break;
            }
        }
    }
}
?>

<div class="container mt-5">
    <h1 class="text-center">Resultado</h1>
    <?php if ($signo_encontrado): ?>
        <h2 class="text-center"><?= $signo_encontrado->signoNome ?></h2>
        <p class="text-center"><?= $signo_encontrado->descricao ?></p>
    <?php else: ?>
        <p class="text-center">Signo não encontrado!</p>
    <?php endif; ?>
    <a href="index.php" class="btn btn-secondary">Voltar</a>
</div>

<?php include('assets/layouts/footer.php'); ?>
