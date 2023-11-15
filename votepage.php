<!DOCTYPE html>
<html lang="en-US">
    <head>
        <title>VOTE!</title>
        
        <?php require('header_boilerplate.html'); ?>

        <!--TODO: redirect if not logged in-->
        <!--actually, that should also be an include (or require?)-->
        
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
                        <th class="sortable" data-sorttype="numberDesc">
                            <span class="shrinkable down">
                                <span class="long">Current Votes</span>
                                <span class="short">Votes</span>
                            </span>
                        </th>
                        <th class="sortable" data-sorttype="numberDesc">
                            <span class="shrinkable down">
                                <span class="long">Last Voted</span>
                                <span class="short">Last</span>
                            </span>
                        </th>
                        <th class="sortable" data-sorttype="numberDesc">
                            <span class="shrinkable down">
                                <span class="long">Your Votes</span>
                                <span class="short">You</span>
                            </span>
                        </th>
                        <th class="sortable" data-sorttype="numberDesc">
                            <span class="shrinkable down">
                                <span class="long">Total Votes</span>
                                <span class="short">All</span>
                            </span>
                        </th>
                        <th>Vote!</th>
                    </tr>
                    <!-- TODO: replace sample data with PHP -->
                    <tr class="issue">
                        <td data-sortvalue="Minecraft (Vanilla)">
                            <span class="shrinkable right">
                                <span class="big">⛏️</span><span class="long">Minecraft (Vanilla)</span>
                            </span>
                        </td>
                        <td>16</td>
                        <td data-sortvalue="20231025">10/25/23</td> <!-- this will actually be a timestamp, probably, but it should work the same either way-->
                        <td>8</td>
                        <td>20</td>
                        <td><input type="checkbox" form="vote" name="Minecraft (Vanilla)" /></td>
                    </tr>
                    <tr class="issue">
                        <td data-sortvalue="Barony">
                            <span class="shrinkable right">
                                <span class="big">🧌</span><span class="long">Barony</span>
                            </span>
                        </td>
                        <td>8</td>
                        <td data-sortvalue="20231018">10/18/23</td>
                        <td>4</td>
                        <td>8</td>
                        <td><input type="checkbox" form="vote" name="Barony" /></td>
                    </tr>
                    <tr class="game">
                        <td data-sortvalue="Golf with your Friends">
                            <span class="shrinkable right">
                                <span class="big">🏌️‍♀️</span><span class="long">Golf with your Friends</span>
                            </span>
                        ️</td>
                        <td>11</td>
                        <td data-sortvalue="20231018">10/18/23</td>
                        <td>4</td>
                        <td>50</td>
                        <td><input type="checkbox" form="vote" name="Golf With Your Friends" /></td>
                    </tr>
                    <tr class="game">
                        <td data-sortvalue="Duck Game">
                            <span class="shrinkable right">
                                <span class="big">🦆</span><span class="long">Duck Game</span>
                            </span>
                        </td>
                        <td>7</td>
                        <td data-sortvalue="20231004">10/4/23</td>
                        <td>2</td>
                        <td>40</td>
                        <td><input type="checkbox" form="vote" name="Duck Game" /></td>
                    </tr>
                    <tr class="game">
                        <td data-sortvalue="Root: A Game of Woodland Might and Right">
                            <span class="shrinkable right">
                                <span class="big">😾</span><span class="long">Root: A Game of Woodland Might and Right</span>
                            </span>
                        </td>
                        <td>13</td>
                        <td data-sortvalue="20231025">10/25/23</td>
                        <td>5</td>
                        <td>25</td>
                        <td><input type="checkbox" form="vote" name="Root: A Game of Woodland Might and Right" /></td>
                    </tr>
                </table>
                <details class="tight flexcolumn">
                    <summary class="title">Show games with personal issues</summary>
                    <table style="width: 100%;">
                        <tr class="tertiary header">
                            <th class="sortable" data-sorttype="text">Game</th>
                            <th class="sortable" data-sorttype="numberDesc">
                                <span class="shrinkable down">
                                    <span class="long">Current Votes</span>
                                    <span class="short">Votes</span>
                                </span>
                            </th>
                            <th class="sortable" data-sorttype="numberDesc">
                                <span class="shrinkable down">
                                    <span class="long">Last Voted</span>
                                    <span class="short">Last</span>
                                </span>
                            </th>
                            <th class="sortable" data-sorttype="numberDesc">
                                <span class="shrinkable down">
                                    <span class="long">Your Votes</span>
                                    <span class="short">You</span>
                                </span>
                            </th>
                            <th class="sortable" data-sorttype="numberDesc">
                                <span class="shrinkable down">
                                    <span class="long">Total Votes</span>
                                    <span class="short">All</span>
                                </span>
                            </th>
                            <th>Vote!</th>
                        </tr>
                        <!-- TODO: replace sample data with PHP -->
                        <tr class="issue">
                            <td data-sortvalue="Skullgirls">
                                <span class="shrinkable right">
                                    <span class="big">💀</span><span class="long">Skullgirls</span>
                                </span>
                            </td>
                            <td>8</td>
                            <td data-sortvalue="0">Never</td> 
                            <td>0</td>
                            <td>16</td>
                            <td><input type="checkbox" form="vote" name="Skullgirls" /></td>
                        </tr>
                        <tr class="issue">
                            <td data-sortvalue="Pummel Party">
                                <span class="shrinkable right">
                                    <span class="big">🤛</span><span class="long">Pummel Party</span>
                                </span>
                            </td>
                            <td>6</td>
                            <td data-sortvalue="20231018">10/18/23</td>
                            <td>4</td>
                            <td>14</td>
                            <td><input type="checkbox" form="vote" name="Pummel Party" /></td>
                        </tr>
                    </table>
                </details>
                <form id="vote" 
                      action="vote.php" 
                      method="POST" 
                      class="primary footer">
                    <button class="big action" type="submit">Vote!</button>
                </form>
            </main>
        </div>
    </body>
</html>