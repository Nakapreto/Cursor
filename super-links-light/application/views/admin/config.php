<?php
if (!defined('ABSPATH')) {
    die('You are not authorized to access this');
}
$superLinksModel = new SuperLinksModel();
?>

<div class="wrap">
    <div class="container">
        <div class="py-1">
            <div class="row justify-content-end">
                <div class="col-12">
                    <h3><?php TranslateHelper::printTranslate('Configurações') ?></h3>
                </div>
            </div>
            <hr>
        </div>
        <div>
            <div class="card col-md-12">
                <div class="card-body">
                    <h5 class="card-title text-info">Importante:</h5>
                    <div>
                        <div>
                            Se você estiver utilizando um servidor Redis, provavelmente suas métricas não serão
                            contabilizadas. <br>Caso isso aconteça, habilite a função abaixo
                            <div class="mt-3">
                                <form method="post" action="">
                                    <div class="form-group col-md-6">
                                        <select name="enableRedis" class="form-control">
                                            <option value="nao" <?php echo ((isset($_POST['enableRedis']) && $_POST['enableRedis'] == 'nao') || !isset($_POST['enableRedis']) || !get_option('enable_redis_superLinks')) ? 'selected' : ''; ?>>
                                                Não Habilitar métricas no Redis
                                            </option>
                                            <option value="sim" <?php echo ((isset($_POST['enableRedis']) && $_POST['enableRedis'] == 'sim') || get_option('enable_redis_superLinks')) ? 'selected' : ''; ?>>
                                                Habilitar métricas no Redis
                                            </option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <button type="submit" class="btn btn-success">Salvar</button>
                                    </div>
                                </form>
                            </div>
                            <span class="small">Obs.: Se ao habilitar essa função, não for possível ativar as métricas com Redis, ela será automáticamente desabilitada.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
