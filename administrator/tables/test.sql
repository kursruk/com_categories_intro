select 
    a.id,
    a.title,
    a.alias,    
    a.introtext, 
    a.images,
    a.publish_up
from ja_categories c
join ja_contentitem_tag_map m on 
    m.type_alias='com_content.category' and m.content_item_id=c.id
join ja_contentitem_tag_map m2 on m2.type_alias='com_content.article' and
    m2.tag_id = m.tag_id
join ja_content a on a.id = m2.content_item_id
where c.alias='category-2' and a.state=1 and a.publish_down<=current_timestamp
order by a.publish_up desc;


select id from ja_categories where alias='category-2';


$query
->select( ['c.id','c.title','c.description', 'c.alias', 'c.params' ] )
->from($db->quoteName('#__contentitem_tag _map', 'm'))
->join('INNER', $db->quoteName('#__categories', 'c') 
. ' ON ' . $db->quoteName('m.content_item_id') . ' = ' . $db->quoteName('c.id'))
->where(
    $db->quoteName('m.type_alias') . ' = ' . $db->quote('com_content.category')
    .' AND '.$db->quoteName('m.tag_id') . ' = ' . $db->quote($this->params->get('tags'))
)
->order($db->quoteName('c.note') . ' ASC');
