<!DOCTYPE html>
<html lang="en-US">
    <head>
        <title>Game roster</title>
        
        <?php require('header_boilerplate.html'); ?>
        
        <!--TODO: redirect if not logged in-->
        
        <script src="sortutil.js" ></script>
        
    </head>
    <body onload="makeSortablesSortable()">
        <div class="main">
            <a href="homepage.php">
                <img src="fishhat.png" alt="The Fish Hat" class="cap" />
            </a>
            <main class="toplevel secondary tight flexcolumn">
                <table>
                    <tr class="primary header">
                        <th class="sortable" data-sorttype="text">Game</th>
                        <th class="sortable" data-sorttype="numberdesc">
                            <span class="shrinkable down">
                                <span class="short">Pl#</span>
                                <span class="long">Player count</span>
                            </span>
                        </th>
                        <th class="sortable" data-sorttype="text">
                            <span class="shrinkable down">
                                <span class="short">Type</span>
                                <span class="long">Category<wbr />/Genre</span>
                            </span>
                        </th>
                        <th class="sortable" data-sorttype="numberdesc">
                            <span class="shrinkable down">
                                <span class="short">Own?</span>
                                <span class="long">Owned</span>
                            </span>
                        </th>
                        <th class="sortable" data-sorttype="numberdesc">
                            <span class="shrinkable down">
                                <span class="short">Play?</span>
                                <span class="long">Will Play</span>
                            </span>
                        </th>
                        <th>
                        </th>
                    </tr>
                    <!-- TODO: replace sample data with PHP -->
                    <tr class="game">
                        <td data-sortvalue="Minecraft (Vanilla)">
                            <span class="shrinkable right">
                                <span class="big">⛏️</span><span class="long">Minecraft (Vanilla)</span>
                            </span>
                        </td>
                        <td data-sortvalue="9999">1-∞</td>
                        <td>Sandbox</td>
                        <td data-sortvalue="1">✔️</td>
                        <td data-sortvalue="1">✔️</td>
                        <td>
                            <a href="editstatus.php?Minecraft (Vanilla)">
                                <button type="button" class="medium action">Edit</button>
                            </a>
                        </td>
                    </tr>
                    <tr class="game">
                        <td data-sortvalue="Barony">
                            <span class="shrinkable right">
                                <span class="big">🧌</span><span class="long">Barony</span>
                            </span>
                        </td>
                        <td data-sortvalue="4">1-4</td>
                        <td>Roguelike</td>
                        <td data-sortvalue="1">✔️</td>
                        <td data-sortvalue="1">✔️</td>
                        <td>
                            <a href="editstatus.php?Barony">
                                <button type="button" class="medium action">Edit</button>
                            </a>
                        </td>
                    </tr>
                    <tr class="game">
                        <td data-sortvalue="Golf with your Friends">
                            <span class="shrinkable right">
                                <span class="big">🏌️‍♀️</span><span class="long">Golf with your Friends</span>
                            </span>
                        ️</td>
                        <td data-sortvalue="12">1-12</td>
                        <td>Party</td>
                        <td>✔️</td>
                        <td>✔️</td>
                        <td>
                            <a href="editstatus.php?Golf With Your Friends">
                                <button type="button" class="medium action">Edit</button>
                            </a>
                        </td>
                    </tr>
                    <tr class="game">
                        <td data-sortvalue="Duck Game">
                            <span class="shrinkable right">
                                <span class="big">🦆</span><span class="long">Duck Game</span>
                            </span>
                        </td>
                        <td data-sortvalue="8">2-8</td>
                        <td>Brawl</td>
                        <td>✔️</td>
                        <td>✔️</td>
                        <td>
                            <a href="editstatus.php?Duck Game">
                                <button type="button" class="medium action">Edit</button>
                            </a>
                        </td>
                    </tr>
                    <tr class="game">
                        <td data-sortvalue="Root: A Game of Woodland Might and Right">
                            <span class="shrinkable right">
                                <span class="big">😾</span><span class="long">Root: A Game of Woodland Might and Right</span>
                            </span>
                        </td>
                        <td data-sortvalue="6">2-6</td>
                        <td>Board</td>
                        <td>✔️</td>
                        <td>✔️</td>
                        <td>
                            <a href="editstatus.php?Root: A Game of Woodland Might and Right">
                                <button type="button" class="medium action">Edit</button>
                            </a>
                        </td>
                    </tr>
                    <tr class="issue">
                        <td data-sortvalue="Skullgirls">
                            <span class="shrinkable right">
                                <span class="big">💀</span><span class="long">Skullgirls</span>
                            </span>
                        </td>
                        <td data-sortvalue="16">2-16</td>
                        <td>Fighter</td> 
                        <td data-sortvalue="1">✔️</td>
                        <td data-sortvalue="0">
                            <span class="hastip left">❌
                                <span class="tip">You refuse to play Skullgirls.</span>
                            </span>
                        </td>
                        <td>
                            <a href="editstatus.php?Skullgirls">
                                <button type="button" class="medium action">Edit</button>
                            </a>
                        </td>
                    </tr>
                    <tr class="issue">
                        <td data-sortvalue="Pummel Party">
                            <span class="shrinkable right">
                                <span class="big">🤛</span><span class="long">Pummel Party</span>
                            </span>
                        </td>
                        <td data-sortvalue="8">2-8</td>
                        <td>Board</td>
                        <td data-sortvalue="1">✔️</td>
                        <td data-sortvalue="0.25">
                            <span class="hastip left">❗
                                <span class="tip">You have had technical difficulty with Pummel Party.</span>
                            </span>
                        </td>
                        <td>
                            <a href="editstatus.php?Minecraft (Vanilla)">
                                <button type="button" class="medium action">Edit</button>
                            </a>
                        </td>
                    </tr>
                </table>
                <details class="tight flexcolumn">
                    <summary class="title">Show nominated games</summary>
                    <table style="width: 100%;">
                        <tr class="tertiary header">
                            <th class="sortable" data-sorttype="text">Game</th>
                            <th class="sortable" data-sorttype="number">
                                <span class="shrinkable down wide"> <!--we want wide ONLY if there is only one row -->
                                    <span class="short">Pl#</span>
                                    <span class="long">Player count</span>
                                </span>
                            </th>
                            <th class="sortable" data-sorttype="text">
                                <span class="shrinkable down wide">
                                    <span class="short">Type</span>
                                    <span class="long">Category<wbr />/Genre</span>
                                </span>
                            </th>
                            <th>
                                <span class="shrinkable down wide">
                                    <span class="short">Own?</span>
                                    <span class="long">Owned</span>
                                </span>
                            </th>
                            <th>
                                <span class="shrinkable down wide">
                                    <span class="short">Play?</span>
                                    <span class="long">Will Play</span>
                                </span>
                            </th>
                            <th>
                            </th>
                            <th>
                                <span class="shrinkable left">
                                    <span class="short">2</span>
                                    <span class="long">Second</span>
                                </span>
                            </th>
                        </tr>
                        <!-- TODO: replace sample data with PHP -->
                        <tr class="game">
                            <td data-sortvalue="Terraria">
                                <span class="shrinkable right">
                                    <span class="big">🌳</span><span class="long">Terraria</span>
                                </span>
                            </td>
                            <td data-sortvalue="9999">1-∞</td>
                            <td>Sandbox</td>
                            <td>✔️</td>
                            <td>✔️</td>
                            <td>
                                <a href="editstatus.php?Terraria">
                                    <button type="button" class="medium action">Edit</button>
                                        
                                </a>
                            </td>
                            <td>
                                <span class="hastip left">
                                    <input type="checkbox" form="second" name="second" value="Terraria" disabled \>
                                    <span class="tip">You cannot second your own nomination.</span>
                                </span>
                            </td>
                        </tr>
                        <tr class="game">
                            <td data-sortvalue="Space Engineers">
                                <span class="shrinkable right">
                                    <span class="big">👩‍🚀</span><span class="long">Space Engineers</span>
                                </span>
                            </td>
                            <td data-sortvalue="9999">1-∞</td>
                            <td>Sandbox</td>
                            <td>✔️</td>
                            <td>✔️</td>
                            <td>
                                <a href="editstatus.php?Space Engineers">
                                    <button type="button" class="medium action">Edit</button>
                                </a>
                            </td>
                            <td><input type="checkbox" form="second" name="second" value="Space Engineers" \>
                            </td>
                        </tr>
                        <tr class="issue">
                            <td data-sortvalue="Civ VI Pirates">
                                <span class="shrinkable right">
                                    <span class="big">🏴‍☠️</span><span class="long">Civ VI Pirates</span>
                                </span>
                            </td>
                            <td data-sortvalue="4">2-4</td>
                            <td>Board</td>
                            <td>✔️</td>
                            <td>
                                <span class="hastip left">❌
                                    <span class="tip">You refuse to play Civ VI Pirates.</span>
                                </span>
                            </td>
                            <td>
                                <a href="editstatus.php?Civ VI Pirates">
                                    <button type="button" class="medium action">Edit</button>
                                </a>
                            </td>
                            <td><input type="checkbox" form="second" name="second" value="Civ VI Pirates" \>
                            </td>
                        </tr>
                        <tr class="game">
                            <td data-sortvalue="Jackbox">
                                <span class="shrinkable right">
                                    <span class="big">💩</span><span class="long">Jackbox</span>
                                </span>
                            </td>
                            <td data-sortvalue="16">1-16</td>
                            <td>Party</td>
                            <td data-sortvalue="0.75">
                                <span class="hastip left">⭕
                                    <span class="tip">You do not own Jackbox, but only the host needs it.</span>
                                </span>
                            </td>
                            <td>✔️</td>
                            <td>
                                <a href="editstatus.php?Jackbox">
                                    <button type="button" class="medium action">Edit</button>
                                </a>
                            </td>
                            <td><input type="checkbox" form="second" name="second" value="Jackbox" \>
                            </td>
                        </tr>
                        <tr class="game">
                            <td data-sortvalue="Codenames">
                                <span class="shrinkable right">
                                    <span class="big">🕵️</span><span class="long">Codenames</span>
                                </span>
                            </td>
                            <td data-sortvalue="9999">2-∞</td>
                            <td>Board</td>
                            <td data-sortvalue="1">
                                <span class="hastip left">➖
                                    <span class="tip">Ownership is not required for Codenames.</span>
                                </span>
                            </td>
                            <td>✔️</td>
                            <td>
                                <a href="editstatus.php?Codenames">
                                    <button type="button" class="medium action">Edit</button>
                                </a>
                            </td>
                            <td><input type="checkbox" form="second" name="second" value="Codenames" \>
                            </td>
                        </tr>
                    </table>
                    <form id="second" 
                          action="second.php" 
                          method="post" 
                          class="primary footer">
                        <button class="big action" type="submit">Second selected</button>
                    </form>
                </details>

            </main>
        </div>
    </body>
</html>