<?php
	
	include_once('auth.php');
	
	$slides_service = new Google_Service_Slides($client);
	
	$pres_title = 'Test_'.uniqid();
	$slide_presentation = new Google_Service_Slides_Presentation([
		'title' => $pres_title,
	]);
	
	$created_presentation = $slides_service->presentations->create($slide_presentation);

    $requests = array();
	$slidesService = new Google_Service_Slides($client);
	
	/**SECOND SLIDE */
		$slide_id = 'unique_slide_id_'.uniqid();
		$slide_main_title_id = 'title_'.uniqid();
		$requests[] = new Google_Service_Slides_Request([
				'createSlide' => [
					'objectId' => $slide_id,
					'slideLayoutReference' => [
						'predefinedLayout' => 'BLANK'
					],
				]
			]
		);
	/**SECOND SLIDE */

	/**SECOND SLIDE LOGO*/
		$logoUrl = 'https://i.postimg.cc/v8qsMF74/logo.png';
		$logoId = 'logo_image_'.uniqid();
		$requests[] = new Google_Service_Slides_Request(array(
			'createImage' => array(
				'objectId' => $logoId,
				'url' => $logoUrl,
				'elementProperties' => array(
					'pageObjectId' => $slide_id,
					'size' => array(
                        'height' => ['magnitude' => 25, 'unit' => 'PT'],
                        'width' => ['magnitude' => 30, 'unit' => 'PT'],
					),
					'transform' => array(
						'scaleX' => 1,
						'scaleY' => 1,
						'translateX' => 20,
                        'translateY' => 25,
                        'unit' => 'PT'
					)
				)
			)
		));
	/**SECOND SLIDE LOGO*/

	/**SECOND SLIDE TITLE*/
		$textid = 'MyTextBox_01'.uniqid();
        $requests[] = new Google_Service_Slides_Request(array(
            'createShape' => array(
                'objectId' => $textid,
                'shapeType' => 'TEXT_BOX',
                'elementProperties' => array(
                    'pageObjectId' => $slide_id,
                    'size' => array(
                        'height' => ['magnitude' => 30, 'unit' => 'PT'],
                        'width' => ['magnitude' => 300, 'unit' => 'PT']
                    ),
                    'transform' => array(
                        'scaleX' => 1,
                        'scaleY' => 1,
                        'translateX' => 50,
                        'translateY' => 25,
                        'unit' => 'PT'
                    )
                )
            )
        ));

        $requests[] = new Google_Service_Slides_Request(array(
            'insertText' => array(
                'objectId' => $textid,
                'insertionIndex' => 0,
                'text' => 'Productos Alimenticios – Opción 1'
            )
        ));
	/**SECOND SLIDE TITLE*/

	/**SECOND SLIDE IMAGE 1*/
		$imageUrl = 'https://i.postimg.cc/3Nc5ZmrX/0.jpg';
		$imageId = 'image_'.uniqid();
		$emu4M = array('magnitude' =>8000000, 'unit' => 'EMU');
		$requests[] = new Google_Service_Slides_Request(array(
			'createImage' => array(
				'objectId' => $imageId,
				'url' => $imageUrl,
				'elementProperties' => array(
					'pageObjectId' => $slide_id,
					//'size' => array(
					//	'height' => $emu4M,
					//	'width' => $emu4M
					//),
					//'transform' => array(
					//	'scaleX' => 1,
					//	'scaleY' => 1,
					//	'translateX' => 100000,
					//	'translateY' => 100000,
					//	'unit' => 'EMU'
					//),
					'size' => array(
					    'height' => ['magnitude' => 640, 'unit' => 'PT'],
						'width' => ['magnitude' => 360, 'unit' => 'PT'],
					),
					'transform' => array(
						'scaleX' => 1,
						'scaleY' => 1,
						'translateX' => 20,
						'translateY' => -50,
						'unit' => 'PT'
					),
				)
			)
		));
	/**SECOND SLIDE IMAGE 1*/

	/**EXECUTE */
		$batchUpdateRequest = new Google_Service_Slides_BatchUpdatePresentationRequest(array(
			'requests' => $requests
		));
		
		try {
			$response = $slidesService->presentations->batchUpdate($created_presentation->presentationId, $batchUpdateRequest);
			//$createImageResponse = $response->getReplies()[0]->getCreateImage();
			//printf("Created image with ID: %s\n", $createImageResponse->getObjectId());
			echo json_encode(['status'=>200,
											'slides'=>$created_presentation->slides,
											'msg'=>"https://docs.google.com/presentation/d/".$created_presentation->presentationId."/edit"]);

		}catch (\Throwable $e) {
			http_response_code(400);
			error_log('['.date('Y-m-d H:i:s').'] '.$e.PHP_EOL , 3, 'error.log');
			echo json_encode(['status'=>400,'error'=>$e->getMessage()]);
		}
	/**EXECUTE */
