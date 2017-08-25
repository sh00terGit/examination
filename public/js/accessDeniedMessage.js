/* 
 *
 * @author Shipul Andrey 
 *  @position Nod-4 ivc
 * Скрипт вывода сообщения для ссылок с закрытым доступом
 * 
 * if($this->userIdentity->subdivision_id === $exam->getManager()->getSubDivision()->getId() ) {
                $urlAddQuestions = $this->url(array('id' => $exam->getId(), 'typeBilet' => $exam->getType()), 'adminExamFilling');
                $urlDelete = $this->url(array('id' => $exam->getId(), 'archive' => 1), 'adminExamDelete');
                $urlUpdate = $this->url(array('id' => $exam->getId()), 'adminExamEdit') ;
            } else {
               $urlUpdate = $urlDelete = $urlAddQuestions = '#accessDenied';
                
            }
             
 * 
 * 
 */

$(document).ready(function () {
    var links;
    links  = document.getElementsByTagName('a');
    $(links).each(function () {
        if($(this).attr('href') === '#accessDenied') {
        $(this).attr('onClick',"alert('У вас нет прав на эту операцию')");
    }
    });
});
