<?php
		
	include_once('auth.php');

	function clonePresentationByName(Google_Service_Drive $driveService, $copyName, $template)
	{
		$response = $driveService->files->listFiles([
			'q' => "mimeType='application/vnd.google-apps.presentation' and name='".$template."'",
			'spaces' => 'drive',
			'fields' => 'files(id, name)',
		]);
		if($response->files){
			$templatePresentationId = $response->files[0]->id;
		} else {
			throw new Exception('Template presentation not found');
		}

		$copy = new Google_Service_Drive_DriveFile([
			'name' => $copyName
		]);
		$driveResponse = $driveService->files->copy($templatePresentationId, $copy);

		return $driveResponse->id;
	}

	function requestReplaceText($placeholder, $replacement)
	{
		return new Google_Service_Slides_Request([
			'replaceAllText' => [
				'containsText' => [
					'text' => $placeholder,
					'matchCase' =>  true,
				],
				'replaceText' => $replacement
			]
		]);
	}

	function requestReplaceShapesWithImage($shapeText, $imageUrl)
	{
		return new Google_Service_Slides_Request([
			'replaceAllShapesWithImage' => [
				'containsText' => [
					'text' => $shapeText,
					'matchCase' =>  true,
				],
				'imageUrl' => $imageUrl,
				'replaceMethod' => 'CENTER_INSIDE',
			]
		]);
	}

	function executeRequests(Google_Service_Slides $slidesService, $presentationId, $requests)
	{
		$batchUpdateRequest = new Google_Service_Slides_BatchUpdatePresentationRequest([
			'requests' => $requests
		]);

		$slidesService->presentations->batchUpdate($presentationId, $batchUpdateRequest);
	}

	function replaceContent(Google_Service_Slides $slidesService, $presentationId, $texts,$images)
	{
		$requests = [];

		//$requests[] = requestReplaceText('{{tittle_1}}', 'Contenido 1');

		foreach ($texts as $key => $value) {
			$requests[] = requestReplaceText($key, $value);
		}

		foreach ($images as $key => $value) {
			$requests[] = requestReplaceShapesWithImage($key, $value);
		}

		executeRequests($slidesService, $presentationId, $requests);
	}

	try {
		//$client = getClient();
		$driveService = new Google_Service_Drive($client);
		$slidesService = new Google_Service_Slides($client);
		$template = $_POST['template'];

		switch ($template) {
			case '1':
				$og_template = 'Template_1';
				
				$images = [];
				$images['{{image_1_1}}'] = 'https://i.ibb.co/tD1NB4V/image.jpg';
				$images['{{image_2_1}}'] = 'https://i.ibb.co/cgJd46H/1.jpg';
				$images['{{image_3_1}}'] = 'https://i.ibb.co/0FMkmKL/2.jpg';
				$images['{{image_4_1}}'] = 'https://i.ibb.co/1MPJqRS/3.jpg';
				$images['{{image_5_1}}'] = 'https://i.ibb.co/f9LZGRv/4.jpg';

				$texts = [];
				$texts['{{titulo_1}}'] = 'Productos Alimenticios – Opción 1';
				$texts['{{direccion_1}}'] = 'Av. Nicolás de Piérola 419 Urb. Centro poblado Santa Clara, Ate';
				$texts['{{opcion_1}}'] = 'Ate – Opción 1';
				$texts['{{area_1}}'] = '1,800 m2';
				$texts['{{metraje_1}}'] = '20 ml.';
				$texts['{{area_construida_1}}'] = '1,600 m2';
				$texts['{{zonificacion_1}}'] = 'I2 (Industria Liviana)';
				$texts['{{altura_1}}'] = '4 pisos (12 ml.)';
				$texts['{{precio_alquiler_metro_1}}'] = '$4.5 m2 + IGV';
				$texts['{{precio_alquiler_total_1}}'] = '$ 8,100 + IGV ';
				$texts['{{precio_venta_metro_1}}'] = '';
				$texts['{{precio_venta_total_1}}'] = '';
				$texts['{{caracteristicas_1}}'] = 'Avenida de tráfico zonal bajo, ubicado entre el grifo Repsol y el Centro Pallasca. Local cuenta con áreas como guardianía o caseta de control, áreas administrativas amplias, SSHH para personal de producción, damas y caballeros, zona de almacenaje de materias primas, zona de producción, zona de productos terminados, oficinas de producción, patio de maniobras, reservorio de agua elevado 12 m3. ';

				break;
			case '2':
				$og_template = 'Template_2';

				$images = [];
				$images['{{image_1_1}}'] = 'https://i.ibb.co/tD1NB4V/image.jpg';
				$images['{{image_2_1}}'] = 'https://i.ibb.co/cgJd46H/1.jpg';
				$images['{{image_3_1}}'] = 'https://i.ibb.co/0FMkmKL/2.jpg';
				$images['{{image_4_1}}'] = 'https://i.ibb.co/1MPJqRS/3.jpg';
				$images['{{image_5_1}}'] = 'https://i.ibb.co/f9LZGRv/4.jpg';

				$images['{{image_1_2}}'] = 'https://i.ibb.co/j892FvP/5.jpg';
				$images['{{image_2_2}}'] = 'https://i.ibb.co/Jn034q1/6.jpg';
				$images['{{image_3_2}}'] = 'https://i.ibb.co/9YbKJfZ/7.jpg';
				$images['{{image_4_2}}'] = 'https://i.ibb.co/k2SZwJ9/8.jpg';
				$images['{{image_5_2}}'] = 'https://i.ibb.co/k2SZwJ9/8.jpg';

				$texts = [];
				$texts['{{titulo_1}}'] = 'Productos Alimenticios – Opción 1';
				$texts['{{direccion_1}}'] = 'Av. Nicolás de Piérola 419 Urb. Centro poblado Santa Clara, Ate';
				$texts['{{opcion_1}}'] = 'Ate – Opción 1';
				$texts['{{area_1}}'] = '1,800 m2';
				$texts['{{metraje_1}}'] = '20 ml.';
				$texts['{{area_construida_1}}'] = '1,600 m2';
				$texts['{{zonificacion_1}}'] = 'I2 (Industria Liviana)';
				$texts['{{altura_1}}'] = '4 pisos (12 ml.)';
				$texts['{{precio_alquiler_metro_1}}'] = '$4.5 m2 + IGV';
				$texts['{{precio_alquiler_total_1}}'] = '$ 8,100 + IGV ';
				$texts['{{precio_venta_metro_1}}'] = '$ 10.2 m2 + IGV';
				$texts['{{precio_venta_total_1}}'] = '$ 55.500 + IGV';
				$texts['{{caracteristicas_1}}'] = 'Avenida de tráfico zonal bajo, ubicado entre el grifo Repsol y el Centro Pallasca. Local cuenta con áreas como guardianía o caseta de control, áreas administrativas amplias, SSHH para personal de producción, damas y caballeros, zona de almacenaje de materias primas, zona de producción, zona de productos terminados, oficinas de producción, patio de maniobras, reservorio de agua elevado 12 m3. ';

				$texts['{{titulo_2}}'] = 'Local – Opción 2';
				$texts['{{direccion_2}}'] = 'Cl. Santa Lucia 139, Urb. Los Sauces, Ate Vitarte';
				$texts['{{opcion_2}}'] = 'Ate – Opción 2';
				$texts['{{area_2}}'] = '1,100 m2';
				$texts['{{metraje_2}}'] = '19.50 ml.';
				$texts['{{area_construida_2}}'] = '1.100 m2';
				$texts['{{zonificacion_2}}'] = 'I2 (Industria Liviana)';
				$texts['{{altura_2}}'] = '4 pisos (12 ml.)';
				$texts['{{precio_alquiler_metro_2}}'] = '$ 6.6 m2 + IGV';
				$texts['{{precio_alquiler_total_2}}'] = '$ 6,600 + IGV';
				$texts['{{precio_venta_metro_2}}'] = '$ 25.5 m2 + IGV';
				$texts['{{precio_venta_total_2}}'] = '$ 25.000 + IGV';
				$texts['{{caracteristicas_2}}'] = 'Zona céntrica de Ate Vitarte cerca a San Luis 
				Cerca a las principales vías de acceso como, Carretera Central, Av. Las Torres, Circunvalación';

				break;
			default:
				$og_template = '';
				break;
		}
		$new_slide = $og_template.'_'.uniqid();
		$presentationId = clonePresentationByName($driveService, $new_slide,$og_template);
		replaceContent($slidesService, $presentationId, $texts,$images);

		echo json_encode(['status'=>200,
										'msg'=>"https://docs.google.com/presentation/d/".$presentationId."/edit"]);

	}catch (\Throwable $e) {
		http_response_code(400);
		error_log('['.date('Y-m-d H:i:s').'] '.$e.PHP_EOL , 3, 'error.log');
		echo json_encode(['status'=>400,'error'=>$e->getMessage()]);
	}
