<?php

namespace App\Http\Modules\DocWidgetStructure\Services;

use Exception;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use App\Http\Modules\DocWidgetStructure\Responses\DocWidgetResponse;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use App\Http\Modules\DocWidgetStructure\Repositories\DocWidgetRepository;

class DocWidgetService
{
    /**
     * Repository instance for handling database interactions.
     *
     * @var DocWidgetRepository
     */
    protected $docWidgetRepository;

    /**
     * Initialize the service with the specified repository.
     *
     * @param DocWidgetRepository $docWidgetRepository
     */
    public function __construct(DocWidgetRepository $docWidgetRepository)
    {
        $this->docWidgetRepository = $docWidgetRepository;
    }

    /**
     * Get the widget structure and document details.
     *
     * @param int $caseId
     * @param int $widgetId
     * @return array
     */
    public function getWidgetStructure($caseId, $widgetId)
    {
        try {
            //get widget with tab and placeholder and its documents structure by case and widget wise
            $widgetStructure = $this->docWidgetRepository->getWidgetStructure($caseId, $widgetId);

            //if no widget structure found return null else proceed for genertaing widget structure response
            if(!empty($widgetStructure)){
                $responseDto = new DocWidgetResponse();

                $responseDto->widgetName = $widgetStructure[0]->widget_name;

                //generate tab wise placeholder structure
                foreach ($widgetStructure as $item) {
                    $tabId = $item->tab_id;

                    if (!isset($tabs[$tabId])) {
                        $tabs[$tabId] = [
                            'tabId' => $tabId,
                            'tabName' => $item->tab_name,
                            'placeholders' => []
                        ];
                    }

                    $placeholders = [
                        'placeholderId' => $item->placeholder_id,
                        'placeholderName' => $item->placeholder_name,
                        'required' => $item->is_placeholder_required ? true : false,
                        'docId' => $item->doc_id,
                        'docUrl' => $item->file_url,
                        'status' => $item->status, // You need to determine the status here
                        'rejectionReasonId' => $item->rejection_reason_id
                    ];

                    $tabs[$tabId]['placeholders'][] = $placeholders;

                    //TODO: get widget tab wise rejection reasons
                  //  $rejectionReasons = [];

                   // $tabs[$tabId]['rejectionReasons'] = $rejectionReasons;
                }

                $responseDto->tabs = array_values($tabs); // Reset array keys

                return $responseDto;
            }else{
                return null;
            }

        }  catch (BadRequestHttpException $e) {
            throw $e;
        }catch (NotFoundHttpException $e) {
            throw $e;
        } catch (Exception $e) {
            throw $e;
        }
    }


}
