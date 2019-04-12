<?php 

/*

AntiFlood basic by @OmbraOscura

Potrebbe presentare dei bug, sono le 00:38 e sto mezzo addormito, datevi da fare per fixare i piccoli errori che ho lasciato<3

*/

$msgID = $msgid; #xd

if($chatID < 0) {

  $db->query('CREATE TABLE IF NOT EXISTS AntiFlood (user_id INT, chat_id BIGINT, messages TEXT, time BIGINT) ENGINE=INNODB');
  
  $q = $db->prepare('SELECT * FROM AntiFlood WHERE user_id = ? AND chat_id = ?');
  $q->execute([$userID, $chatID]);
  
  
  if($q->rowCount() == 0) {
  
    $db->prepare('INSERT INTO AntiFlood (user_id, chat_id, messages, time) VALUES (?,?,?,?)')->execute([$userID, $chatID, $msgID, time()]);
   
  } else {
  
    $f = $q->fetch(PDO::FETCH_ASSOC);
  
    $db->prepare('UPDATE AntiFlood SET time = ?, messages = ? WHERE chat_id = ? AND user_id = ?')->execute([time(), $f['messages'] . ' ' . $msgID, $chatID, $userID]);
  
    $q = $db->prepare('SELECT * FROM AntiFlood WHERE user_id = ? AND chat_id = ?');
    $q->execute([$userID, $chatID]);
  
    $f = $q->fetch(PDO::FETCH_ASSOC);
  
    if(count(explode(' ', $f['messages'])) >= 5 && $f['time']+3 >= time()) {
    
      ban($chatID, $userID);
      sm($chatID, '<a href=\'tg://user?id=' . $userID . '\'>' . $nome . '</> bannato per flood.');
      
      foreach(explode(' ', $f['messages']) as $m_id) {
        
        dm($chatID, $m_id);
      
      }
    
    } elseif (count(explode(' ', $f['messages'])) <= 5 && $f['time']+3 <= time()) {
    
      $db->prepare('UPDATE AntiFlood SET time = ?, messages = ? WHERE chat_id = ? AND user_id = ?')->execute([time(), $msgID, $chatID, $userID]);
      
    }
    
  }

}
