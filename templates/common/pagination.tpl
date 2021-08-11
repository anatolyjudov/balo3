{if !$shown_single_page}
<p>{if $shown_rows_from>1}<a style="text-decoration: none;" href="./?{if $shown_rows_prev_skip > 0}skip={$shown_rows_prev_skip}{if $letter neq ''}&{/if}{/if}{if $letter neq ''}letter={$letter}{/if}"><<<<</a>{/if}

<span style="margin-left: 50px; margin-right: 50px;">[{$shown_rows_from}-{$shown_rows_to}] из {$results_count}</span>

{if $shown_rows_to < $results_count}<a style="text-decoration: none;" href="./?skip={$shown_rows_next_skip}{if $letter neq ''}&letter={$letter}{/if}">>>>></a>{/if}</p>
{/if}