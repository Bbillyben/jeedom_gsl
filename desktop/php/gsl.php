<?php
if (!isConnect('admin')) {
    throw new Exception('{{401 - Accès non autorisé}}');
}
$plugin = plugin::byId('gsl');
sendVarToJS('eqType', $plugin->getId());
$eqLogics = eqLogic::byType($plugin->getId());
?>

    <div class="row row-overflow">
        <div class="col-lg-12 col-md-9 col-sm-8 eqLogicThumbnailDisplay" style="border-left: solid 1px #EEE; padding-left: 25px;">
            <legend><i class="fa fa-cog"></i> {{Gestion}}</legend>
            <div class="eqLogicThumbnailContainer">
                <div class="cursor eqLogicAction logoPrimary" data-action="add">
                    <i class="fas fa-plus-circle"></i>
                    <br>
                    <span>{{Ajouter}}</span>
                </div>
                <div class="cursor eqLogicAction logoSecondary" data-action="gotoPluginConf">
                    <i class="fas fa-wrench"></i>
                    <br>
                    <span>{{Configuration}}</span>
                </div>
            </div>
            <legend><i class="fa fa-table"></i> {{Mes contacts}}</legend>
            <div class="eqLogicThumbnailContainer">
                <?php
                foreach ($eqLogics as $eqLogic) {
                    $opacity = ($eqLogic->getIsEnable()) ? '' : jeedom::getConfiguration('eqLogic:style:noactive');
                    echo '<div class="eqLogicDisplayCard cursor '.$opacity.'" data-eqLogic_id="' . $eqLogic->getId() . '" >';
                    $imageCmd = $eqLogic->getCmd(null, 'image');
                    if(is_object($imageCmd)){
                        $image = $imageCmd->execCmd();
                        if($image){
                            echo '<img src="/plugins/gsl/core/ajax/gsl.proxy.php?url=' . $image . '" style="border-radius:50%; position: absolute; width: 65px !important; height: 65px !important;left: 32px; top: 20px; padding-top:inherit;min-height:inherit !important;" />';
                        }
                    }
                    echo '<img src="' . $plugin->getPathImgIcon() . '"   />';
                    echo '<br>';
               		echo '<span class="name">' . $eqLogic->getHumanName(true, true) . '</span>';
                    echo '</div>';
                }
                ?>
            </div>
        </div>

        <div class="col-lg-12 col-md-9 col-sm-8 eqLogic" style="border-left: solid 1px #EEE; padding-left: 25px;display: none;">
            <a class="btn btn-success eqLogicAction pull-right" data-action="save"><i class="fa fa-check-circle"></i> {{Sauvegarder}}</a>
            <a class="btn btn-danger eqLogicAction pull-right" data-action="remove"><i class="fa fa-minus-circle"></i> {{Supprimer}}</a>
            <a class="btn btn-default eqLogicAction pull-right" data-action="configure"><i class="fa fa-cogs"></i> {{Configuration avancée}}</a>
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation"><a href="#" class="eqLogicAction" aria-controls="home" role="tab" data-toggle="tab" data-action="returnToThumbnailDisplay"><i class="fa fa-arrow-circle-left"></i></a></li>
                <li role="presentation" class="active"><a href="#eqlogictab" aria-controls="home" role="tab" data-toggle="tab"><i class="fa fa-tachometer"></i> {{Equipement}}</a></li>
                <li role="presentation"><a href="#commandtab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fa fa-list-alt"></i> {{Commandes}}</a></li>
            </ul>
            <div class="tab-content" style="height:calc(100% - 50px);overflow:auto;overflow-x: hidden;">
                <div role="tabpanel" class="tab-pane active" id="eqlogictab">
                    <br/>
                    <form class="form-horizontal">
                        <fieldset>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">{{Nom de l'équipement}}</label>
                                <div class="col-sm-3">
                                    <input type="text" class="eqLogicAttr form-control" data-l1key="id" style="display : none;"/>
                                    <input type="text" class="eqLogicAttr form-control" data-l1key="name" placeholder="{{Nom de l'équipement}}"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">{{Objet parent}}</label>
                                <div class="col-sm-3">
                                    <select id="sel_object" class="eqLogicAttr form-control" data-l1key="object_id">
                                        <option value="">{{Aucun}}</option>
                                        <?php
                                        foreach (jeeObject::all() as $obj) {
                                            echo '<option value="' . $obj->getId() . '">' . $obj->getName() . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">{{Catégorie}}</label>
                                <div class="col-sm-9">
                                    <?php
                                    foreach (jeedom::getConfiguration('eqLogic:category') as $key => $value) {
                                        echo '<label class="checkbox-inline">';
                                        echo '<input type="checkbox" class="eqLogicAttr" data-l1key="category" data-l2key="' . $key . '" />' . $value['name'];
                                        echo '</label>';
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label"></label>
                                <div class="col-sm-9">
                                    <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isEnable" checked/>{{Activer}}</label>
                                    <label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isVisible" checked/>{{Visible}}</label>
                                </div>
                            </div>
                            <div class="form-group gls-contact">
                                <label class="col-sm-3 control-label">{{Commande}}</label>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                        <input class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="cmdgeoloc">
                                        <span class="input-group-btn">
                                             <a class="btn btn-default cursor listEquipementAction" data-input="cmdgeoloc"><i class="fa fa-list-alt "></i></a>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group gls-contact">
                                <label class="col-sm-3 control-label">{{Visible sur le global}}</label>
                                <div class="col-sm-3">
                                    <input type="checkbox" class="eqLogicAttr" data-l1key="configuration" data-l2key="isVisibleGlobal"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">{{Visible sur le panel}}</label>
                                <div class="col-sm-3">
                                    <input type="checkbox" class="eqLogicAttr" data-l1key="configuration" data-l2key="isVisiblePanel"/>
                                </div>
                            </div>
			    <div class="form-group">
                                <label class="col-sm-3 control-label">{{Historique affiché (heures)}}
                                <sup><i class="fas fa-question-circle tooltipstered" title="Durée en heure de l'historique affiché sur le widget. Nécessite que la commande coordonnees soit historisée. Ne pas renseigner pour désactiver."></i></sup>
                                </label>
                                <div class="col-sm-3">
                                    <input type="number" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="history">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">{{Historique affiché sur le global (heures)}}<sup><i class="fas fa-question-circle tooltipstered" title="Durée en heure de l'historique affiché sur le widget global. Nécessite que la commande coordonnees soit historisée. Ne pas renseigner pour désactiver."></i></sup></label>
                                <div class="col-sm-3">
                                    <input type="number" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="historyGlobal">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">{{Type de coordonnées}}</label>
                                <div class="col-sm-3">
                                    <select class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="coordinatesType">
                                        <option value="">{{Fixe}}</option>
                                        <option value="cmd">{{Commande Localisation}}</option>
                                        <option value="jeedom">{{Jeedom}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">{{Coordonnées}}<sup><i class="fas fa-question-circle tooltipstered" title="Latitude,longitude"></i></sup></label>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                    	<input id="coordinated" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="coordinated"/>
                                    	<span class="input-group-btn"> 
                                            <a class="btn btn-default cursor listCmdActionMessage tooltips cmdSendSel" title="{{Rechercher une commande}}" data-input="#coordinated"><i class="fas fa-list-alt"></i></a> 
										</span> 
                                	</div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">{{Commande Précision}}<sup><i class="fas fa-question-circle tooltipstered" title="precision en mètre"></i></sup></label>
                                <div class="col-sm-3">
                                    <div class="input-group">
                                    	<input id="coordinated_precision" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="coordinated_precision"/>
                                    	<span class="input-group-btn"> 
                                            <a class="btn btn-default cursor listCmdActionMessage tooltips cmdSendSel" title="{{Rechercher une commande}}" data-input="#coordinated_precision"><i class="fas fa-list-alt"></i></a> 
										</span> 
                                	</div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">{{Couleur}}</label>
                                <div class="col-sm-3">
                                    <input type="color" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="color">
                                </div>
                            </div>                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label">{{Filtrer la précision}}</label>
                                <div class="col-sm-3">
                                    <input type="checkbox" class="eqLogicAttr" data-l1key="configuration" data-l2key="precisionFiltre"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">{{Précision (m)}}</label>
                                <div class="col-sm-3">
                                    <input type="number" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="precision">
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>
                <div role="tabpanel" class="tab-pane" id="commandtab">
                    <table id="table_cmd" class="table table-bordered table-condensed">
                        <thead>
                        <tr>
                            <th width="150px">{{Nom}}</th>
							<th>{{Etat}}</th>
                            <th width="100px">{{Action}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

<?php include_file('desktop', 'gsl', 'js', 'gsl');?>
<?php include_file('core', 'plugin.template', 'js');?>
