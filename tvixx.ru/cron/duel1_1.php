<?php

require_once 'bd.php';

$names = [
    ["Terminatorzzz", "Playbou2000", "Mol4alivyiBob", "RealNaruto",
        "SaskeRulit", "Dimon", "Bumbu44a", "SuperKuiiii", "KPyTOUMa4o", "Hulk78",
        "Forsaken", "Lait", "NarutoXxX", "GomeR", "MereMan", "TaRaKaN", "FanBoy",
        "Filimonov", "TANK",
        "Be3yN4uk", "Странник", "Mr.Nobody", "ηỖЌỖƤú₸ẸԉЬ", "Strannik", "сержант диванных битв", "Ghostbuster", "Dreammer", "DiGiTaL", "Reimon", "Шаман", "Ѣѐλӹй★Ҟоҭ", "Злобный Пупс", "Pro-Bro", "Skyler", "Puhlik403", "=)Allecks=)", "ℬᎯᗫℑᙢ", "Killer_Man", "TheBrain", "Гѻрдӹй★ПᎮúнц", "Death_Twilight", "Talisman", "Partyzan", "400kg", "Hattab", "Forumen", "мартовский кот", "✗ÄҜỂ₱", "default", "FBI", "Возвращение_тузика", "*Deklan*", "AKG", "AnimeNeko", "H1net", "ICER", "L*I*I*O*N", "MarkJaquith", "Senator", "гризли", "Типичный Француз", "Alienovod", "Andreich", "AnimeRedDragon", "BFG_On", "crypt0", "DosS", "GAY", "Kalleos", "MasterK", "Mr DokDozeL", "Neoblink", "Raptor", "Rezak", "TWiSTED", "Wulfy", "xxxx", "Yarik", "Золотой", "провайдер", "Ӎ҉ลթนус", "Ŵ Ă Ĥ I Ť Ê", "6aRik", "AnimeStar", "Brendon", "Bro", "Da_legend", "Defender", "EvilRick", "Fofan", "jonny2x4", "Kreator", "Marshal", "patriot", "stylin", "Zahar", "князь", "Од1нокий", "3a6aBHbIú", "AnimeOtaku62686", "AnimeQueen_37", "Antawari", "AubsFag", "Azim", "Baner", "BarclaYs", "diego81", "dngr", "Expat", "Fireman", "GyDini", "G_R_I_S_H_A", "Kampot", "Narusegawa", "Objora", "Offer", "philomory", "phishphreek", "redhook_", "Sam", "Shadowness", "StaZ_home", "YAMAHAь", "автогробиль ", "ДенегНет", "ЛЕВ", "Недоступный", " money99", "Ŝňâĸè", "addams", "AESBlowfish", "alasdair5", "AnimeMonkey", "AnimeMun", "AnimeNinja2", "AnimeO-RenIshii", "AnimePriestess", "AnimeRaver1", "AnimeSweetie106", "ArtiGo", "Asskelo", "ATIS", "AzZz1B0", "bender", "BINAN", "BlueSteel", "Boxing", "cdesigns", "DaRamirez", "davidS", "Dima_Baadzhi", "DoM", "DottHellboy", "ECOT", "Fatimal", "Fazamer", "fergun", "fonsi", "Fureks", "Geka", "Gifra", "grint", "gtyroumn", "Gulid", "HAGARE", "hammerhead", "hohol", "Human", "idle0ne", "JorixB", "JOXAV", "KlinT", "Kobzon", "Lev", "malibu", "mesfot", "Mexanuk", "MrBufy", "MrSybian", "Mr™IIe4eHbK@", "noganex_", "noob_", "NUCCA", "Occhi", "openfly", "Pyramide", "Ritor", "root____1", "rufiusblack", "Shawn", "sidny4", "Silke24", "SiSteM32", "sjel_", "SmallBig", "Sokol", "STaiR", "Stalwart", "THC4k", "THE_SFox", "TIIGR", "Tokyo", "UFO_13", "UnoBot", "VoVaN4iK", "webchick", "Zoxan", "агарио", "Атлант ", "Бармалей", "БЕСпринцыпный", "Валентолог", "Гога", "дед максим", "Дровосек", "Котяра", "Купидон", "К[on] Гр[igor]ий", "Ледон", "любовник", "монарХхХ", "пегас", "Рамм", "Ренч", "САНЯ", "Стаф", "Узурпатор", "филипп", "Хирург", "Читатель", "Шальной", "Шизофреник", "Шкипер", "Щекастый", "5енталон", "=ХайLoad=", "ABIREGE", "adams", "adolfo200", "AKEPEVELOV", "alephnull", "Alexeevich", "Andrei", "angeldavid", "Anik", "AnimeMatt", "AnimeNoz", "AnimeOtaku1004", "AnimeOtaku455", "AnimePete", "AnimePhr33k", "AnimePrincess", "AnimePub", "AnimePunk13", "AnimeQueen12", "AnimeQueen2", "AnimeRealistic", "AnimeRocks88", "AnimeShadows91", "AnimeShawn", "AnimeSk8r", "AnimeStuff14", "Anton_Moroz", "ARTHAS", "ASOTAROXI", "Atauz", "AVANGARD", "AvIaToR", "AVTODOKTOR", "BAZOSE", "BblSTRblY", "BeJlukuu", "betty_brash", "BIC", "BIWA", "BJIADUKA", "Blagiy", "blazingice", "Bolm", "BOWEM", "boxubi", "BUHUJEV", "CAVIK", "chauderman", "chuk", "clark", "clop", "cmd", "COJEH", "crochat", "Danger", "danielz", "DbяvoL", "dead25", "DEnoken", "DEPEFIZ", "depesz_", "Dima_MW", "Dimidrol", "Dimka", "Donon", "DragonOutlaw", "dragova", "DRANDO", "Dromar", "dwellshere", "DxekaMix", "dynamo", "EBWAFAS", "EFOXUGOR", "EFYLEHYZ", "EmperorCezar", "En0t", "End3r", "Eric", "Eric-xx", "Erik mc", "evillase_home", "EXOKAZASON", "FAHE", "FlEeFlIcK", "Frenic", "FUI", "Gabon", "gantchev", "Gavrik", "gfteedr",
        "GoodManPro", "GOPOM", "Groove", "Grusha", "guyver6", "halorgium", "hays_", "henriquev_", "hermit_", "HOJIN", "hugozzzz", "huskygoleb", "Iamanub", "IIIaJIyn", "IIIRaM", "IIRC", "IKUVUZE", "IPULY", "IVAH_BROVKIH", "Ivan Comandir", "James cool", "Jary", "JAZUM", "Jokerit", "JRome", "jxmiller", "KAF", "Kappa", "Ke~MEL", "Khmar", "KolT", "koroleviсh", "KoSmoS", "Kosoй", "KOT_MATPOC", "KOVAFAX", "LaGGeRFeeD", "LaIm", "LAMYL", "lapserdak", "lazer", "Lead", "Like^a^Boss", "lioux_", "LodeRunnr", "Loltrek", "LosSantos", "Luckys_", "LUPIV", "m1lasson", "Magnor", "Mahmoud-Afk", "majortool", "MAX 24", "mdew", "mercie", "MEZANYFY", "mikki", "MOCUSO", "monkey123", "MOZI", "mR.Ma1n", "Mr.Zadrot ", "Mr.Винл", "MrArmadillo", "MrAsasIn", "MrClip", "msuse", "MuIIIyTka ToPoR", "Mukola", "MYKI", "NALIL", "NERU", "ness", "NestoR", "NiKoL@й", "NIRO", "Nizkiy", "Nod51", "NOFERYLIB", "nomadsoul_", "Noxis", "NROW", "NUC", "OGYMI", "OHUPIQADOD", "OKIKOR", "okoloW", "Old regret", "OldBush", "OlStar", "OPYFO", "orakel", "Otchim", "owens", "OWEXI", "OXIXIMOB", "PabOu", "PAHAFAR", "paql", "PentooUser_", "pepijn", "perron", "PF-Away", "pfo", "PITABoy", "PITET", "Polar Star-ик", "Popandopala", "Porshe", "Preacher", "PriCaLiST", "r3m", "Radion", "Radius_", "RAMURAMO", "rapont", "RavageX-9", "rbx", "RealMan", "resuDaed", "Rihtger", "robertp", "ROBIN-MD", "ROGITY", "RONY", "rootfiend", "RUNASYRE", "RURI", "RUSE", "Rustam.TTM", "RUTAL", "SamuEL", "sandman10_99", "SASEN", "Saudade", "SBARYRY", "Scart", "sedeki", "SFW", "Shmattie", "Shox", "SHULAR", "sidik", "Silogon", "SkiperDiy", "sladkий", "snooze82", "Snouty", "SOULMAN", "SoulPropagatio1", "sPAck4", "specsmu", "SportMaster", "sradu", "St0rm", "Stavik", "Stefan1800", "Stig", "STIX", "SuBaRu", "tag-", "terminalv", "theghost", "TheMrCake", "TheTerAnH!k", "The_Drunk_BOFH", "TIGYRANE", "toddos", "TonyRanbow", "TopKun", "TOP_LOP", "tostankel", "TOXIMI", "Treborn", "tromp_", "Troyan", "tuck3r", "TUHVI", "tulinka", "Tuxthepenguin", "u19809", "UBEQIDEVAN", "ubung zwei ", "UFOL", "ULAG", "UMANEH", "Unbearable Bear", "urk3l", "URYI", "v33x", "vanquish_", "Vasyliy LUBLYbabovich", "Ve0n", "VEKIK", "Vimer Bro", "VIRSETTI", "Vivanco", "vmwareverk", "Vorgont", "Vornicus", "Vsemogushii", "VUKO", "wh", "wright", "Wumsun", "wuonm", "Wектоr", "Xaleess", "XAVE", "xinram", "Xmas dad", "XYLIHI", "yanfell24", "yapavik", "Yarik Lebed", "zache", "ZaG", "ZETYYVALAT", "ZIRIHIJAN", "ZIVY", "zoom", "Алекс Вор", "Амитеш", "андрей", "Андрей Духовников", "Б♡G_ЛюBvi", "басмен", "Бесценный", "бобёр", "Браер", "ВΔЅТІLLЕ", "Вадим", "Вадим_Груздев", "взолмщик", "Виктор31RUS", "военный_хакер", "Володислав", "Володя", "Волш", "Всё_Что_Горит", "Вульф", "Гоха", "даниил бубликов", "данилка", "ДеД", "Демидов", "дениел", "ДеспоТ", "Джеймс", "Джексон", "джон", "Джон Уик", "Диверсант", "Дмитри Пайе", "Дмитрий", "дядюшка Сэм", "жан", "ЖЕСТОКИЙ", "Застенчивый", "ЗеᏰç", "Ивангай", "івангай", "Капитан Ri", "КАРОЛЬ", "КГБ---АЛЬФА", "кенор", "кирилл", "Космонавт", "Котофаня!!!", "Кпорлд", "крипс", "лихач", "Лысий", "МайкХеллер", "Марнеулский", "Марс", "Махмудов", "МужИкЧ", "Не Наркоман", "Независимый", "никон", "одессит", "Оникс", "очерователь", "пикапер", "Питон", "Плохиши", "просто бродяга", "Р҉о҉д҉и҉о҉н", "Рабат", "равшан", "распылитель", "Реомикс", "Санти", "саша", "САШУЛЯ", "Свɇрло", "СвинТуз", "СеРаФиМ", "СЕРЕЖА", "СИЛЬВЕР", "Сп0рТ1к", "спирт", "спорт1к", "супер снайпер", "ТАНЦОР", "товарищДынин", "Трудовик", "Угон Девушек", "Фирамир", "ХАРД ВОКЕР", "химик", "Холодный", "ХОЛОСТОЙ", "Черпак", "шарфоносец",
        "Эван Питерс", "Эйнштейн", "Юсас", "Ябил", "_clem", "_jdog", "_kite_", "_ldav15", "_tulinka_", "_user", "Ś₱₳ⱤȾ₳₭", "Ⅹо л о с т я к", "Activator", "alexo-andro", "AnimePrincess68", "AnimePuppy", "AnimeShadow", "ANTILOL Dark", "arranon", "ARUZO", "boobaa", "d-brane", "DaGeR", "DarkCook", "Daywalker", "desx", "FANAR", "Farisey", "Father", "fiftyCal", "Francesco Donni", "Gazzl", "ggerman", "JeKo", "jigs", "Melvik", "Mo3r", "mozillaman", "mrinterweb", "Muhak aka Gram", "Navigator", "nilesh111", "NISSAN R-34", "RasisT", "rue_work", "Saveliy2003", "SCHMATKO", "seksboy", "ShadeWisper", "TΣD", "thoron", "True_man_false", "VuLnIx", "wtfman", "yura", "ZAXI", "картофан", "ЌŌ干", "Любящий зять", "Руслан", "Скорпиоша", "ТаТаРиН", "электромантер", "ㅆυჩฉㅆㅆฉΔζøท", "ßع₡ƀ ₮ℬǬŬ", "adat", "alarm", "andy", "AnimeSprite", "Bear", "Cleo", "CULERUTO", "Daerius", "DIMA", "Eeonegay", "Fantomaster", "Farrux", "Frankie Morello", "gruzd", "Haggholm", "ironfroggy_LT", "JARASUBAR", "KEGY", "KieranDOA", "kovan", "Krunk", "MrMadPL", "MR_Dima", "pocek", "vidmac", "Vitalik", "zoro", "альтаир", "артем", "Билларас", "Вовасик", "Дефлоратор", "Лезгин Рамиев", "Маркус", "Мутант", "папик", "Флеш", "ĈâĤьķÅ", "assasin", "azzoz", "Catch", "cipy", "CMcJ", "Cold Dog", "Crazy-Train", "holo", "J-_", "Jahmie", "JakeM3tz", "JD", "Jim Morrison", "Joojabu", "KeepCalmAndWork ", "ReTaLiAToR", "Wesley", "ZALMAN", "Активист", "Ариван", "Беспе4ный", "Буряк", "ВАСАНГО", "Виндохрустик", "Всегда готов", "Всеобщий", "ВЫСГУМ", "Геодезист", "Грузинский нос", "ДиликVip", "Доллар", "Друзь", "Интересующийся", "КАМЕНЬ*", "Квазар", "Ковбой Хагис", "Компик", "Коробо4кин", "Криг", "Майдан", "МедведевСпит", "Мизантроп", "Мо4ильЩик", "МолчаливыЙДЖО", "МОХРАДЖАБ ВИЛИКИЙ", "Надыр", "Неотразимый", "Ополон", "Орранин ", "Паникадило", "Подкованный удачей", "Потишman", "Президент", "Путислав", "Пятница", "Разряженный", "Ротовирусник", "Святопуст", "СЛЕГКА", "Суетливый", "Торвальд", "Учитель", "Azik", "CHefir", "JKnight", "Pendoss", "Безпланшетный", "Дымков", "Заводной Апельсин", "Игронезависимый", "Капитан Америка", "Космонавтик", "Русский агент", "Секретный_файл", "AnimeTenchi", "Chase", "DEGAMAN", "Wolfpaws", "ZeniMax", "Агент", "Георгий_Георгинов", "Дневальный", "ЛюбовникЛцчший", "Меткий стрелок", "МитричЪ", "Социопат", "Троллоло", "Форест_Гам", "Капитализатор", "М-16", "Мясной Рубэн", "Русал", "Максюта", "Мозг", "Космический орк",
    ],
    ["(((Ole4ka)))", "Saske", "Ane4ka92", "Starbuck", "SerpentStar",
        "WereCat", "LePaPaHeTkA", "Angel1991", "StarDust", "KittyGirl", "Marino4ko",
        "Sosita)))", "TotalSin", "Fantastisch", "MaZaXaKa", "StElena",
        "♥ДеϷვκϫя★:Ď♥", "ღMi[SS]✬Kapri[ZZ]ღ", "Ṗṙĭṇṡḙṡṡᾀ", "♥ḾᾶлѐἯь₭ᾶЯ♥", "ЧеРтЁнОк_Ф_кЕдАх", "❤● • [В НеАдЕкВаТе ] • ●❤", "SweetFox", "˙·٠●◇ГолубоглаЗАЯ◇●٠·˙", "-M A N G O-", "..::ᵀᴴᴱ ᴼᴿᴵᴳᴵᴻᴬᴸ::..", "√√√◀ NЕжNыЙ яД ▶√√√", "♔miss.pozzzitifff♔", "ℬḽũƀḽḝηẫᖆ", "Ђ҈Е҈Ǯ҈Ў҉М҉ ҉ӇȺ҉҈Я", "✖Нежнее Ножа✖", "˙˙·٠•● ☆Доступ к [ღ] закрыт ☆●•٠", "С_лЕзВиЕм_в_СеРдЦе", "Шоколадная_фАнТаЗиЯ", "#Радужный Еденорог", "═╬★CoюZнuцa★╬═", "káйфøвàя дéвøчká❤", "*крылатый_Dеmon*", "_*Любовь_Зла_Я_Люблю_Козла*_", "Minikotik", ".Чудо_ф_кЕдахХх.", "♂ ℙe®feⒸt ツ", "ღChocolatE_B♥a♥b♥Yღ", "☆ღRêveღ☆", "Derзkaя", "Б°e°z°y°M°н°а°я", "Кореглазка)", "Ангелочек", "˙˙·٠♔•Never Forget•♔٠·˙˙˙", "?Дьявол_В_раЮ?", "Vanilla", "ИзБаЛоВаНнА пАпОчКоЙ", "PlayGirl", "Влюбленная_В_Ангела", "ОчАрОвАшКа", "Хулиганочка", "*HELEN*", "МаЛеНьКаЯ_ДрЯнЬ", "кошка в кедах", "Miss Kriss", "UFYWERA", "ღღ☺ЭфФеКтНаЯ☺ღღ", "ღ⎠⎛ღ√ Ne@DeK√@Te ღ⎞⎝ღ", "₡ßǿЂǾΔ₦ѦЯ", "ДоЗа СчАсТьЯ", "ЯпОнА МаТь", "Убeй_Meня_Heжнo", "я.°.Тв0й.°.НаРkоТиk", "♥такая как есть♥", "!*°•.ВлюбленA♥в♥Θсень.•°*!", ".ιllιlι.ιl.[►].ιllιlι.ιl.", "Mariy_Kis", "Love_Me", "ПаNда", "NaRkotik", "..::Ợůēēŋ::..", "HollywooD:) ", "Lady Marmalade ", "MissTics", "M&M", "Мусульманка", "P_A_N_I_K_A", "xD...ПсиХбольничКО...xD", "Amnesia", "GirlGAMER", "!!!εγωιστής!!! – эгоист", "Огонек", "˜”*°•CoCo Chanﻉl•°*”˜", "๑۩۩๑K○R○Lеø|(@๑۩۩๑", "@--kukolka--@", "_Одна_Такая_", ":::Eva:::", "CгущЁнга^^", "Леди Водолей", "Tipical_Girl", "Ангел В Кедах", "От А(да) до (ра)Я", "я-вип-ты-влип", "Ǻłēŋǻ", "bloodyMary", "BuSiNkA", "GALAXY_GIRLS", "Vikki", "Нацуми", "хулиганка", "...:::Angelo4ek_ZeLemeNt:::...", "={Ревнивая}=", "Lika_Bes", "((улыбка 45 калибра))", "Без_ума_от_карих_глаз", "ЛИСИЧКА_МИ∩__∩", "***KATUSHA***", "YuiChan", "ة..чё_творю_то?..ة", "Ķρªçøtķª Ħª Çtúλε", "◄◄◄Bl@ck D۞ g►►►", "4ЕРТЕНОК", "EVA", "Miku♥Tян", "4ika bez nika", "Brednaя", "Chumavoi_dinozavrik", "DIVERGENT", "Хар∆κтеᎮ ԋе★цɮеϯочѐҟ", "Angelo4ek_ZeLemeNt", "Køнфētõ4ka", "KoTe", "Přøšŧø я ß ŧvøёm šęřцэ", "Львица", "Нежный яд", "^^ПомидорКа^^", "**AnGeL**", "@*иРиСкА**@", "Bдыҳåй ™", "Night cat", "Pofigist_Ka,", "Брюнеточка", "Доступ к сердцу закрыт", "Микка", "Пафосная девушка", "٭٭((УЖЕ НЕ ТВОЯ))٭٭", "Øçõбêññåя", "˜”*°•.Крылья◐ С۞ветов◐ Чемпи☼н.•°*”˜", "♥_I am С̶у̶п̶е̶р̶м̶э̶н_♥", "......СмЕшНаЯ ДеФкО.....", "?Pantherka?", "BAD GIRL", "DianaFix", "GADuKa", "Милолика", "?•°••Шоколадная_фАнТаЗиЯ?•°••", "DэByШkА uз aдa", "KinDer:)", "lapulechka", "qivi", "Tคΐgค", "tvoi_mechty", "Vanilnaya", "влюбленная столешница", "Эллии", "Юля МанГО", "_-=Я_VетеR=-_", "_Вай_", "▓ ▒ ░ Леди▓ ▒ ░", "☆Шальная Императрица☆", "?WingS_oF_ButterFly?", "I see you", "Krasotka", "MimimiMonster", "NiceGirl", "Ventetta", "я_просто_ангел", "я_та_кoторую_ты_ждaл", "[Fuck-тически_твоя]", "◄►СТРЕЛОЧКА◄►", "-‘?’-Летящяя_звезда-‘?’-", "?????,Сделана_С_Любовью?????,", "?Juliet?", "?”*°•.Life_IS_Life.•°*”?", "AlfabetkA", "Annita", "B A B Y", "Blondy", "EvRiKa", "GlamGirls", "KyS04ek_ZlA", "Ledy dreams", "LLIOK", "LOGIKA", "LoveMe", "Lulaca", "MĮşş_PĀfØZZ", "MrZaika", "PoFIGiSTKa", "Pofigist_Ka", "Альсинка", "вместо солнца", "нику тян", "Чёткая", "[Фся_Такая_Хорошая]", "¢ǿӈ", "((=Лилек=))", "--__М@люТк@__--", "100%%% лЯпОчКа", "3лая 3АФФКА", "aleksey131084", "Altynai", "AngelsLucky", "bestижая", "be_tvoya_igoistka", "BuLo4Ka", "Girls online", "Haruko", "HASHY", "He_Zlaya_Zaya", "Kiss Me", "lady", "Lamionochka", "LeMonadka", "Lost_of_control ", "LOVEGURU", "MeЧтА", "Milady", "Mira_)|(", "Nan Kat", "nice girl", "ochenb_milaya", "oooМанящий_ароматooo", "Simka", "summer rain", "White#mischka", "XSecret", "you_desire", "зАкЛеПкА", "Зелибоба", "М.О.Н.А.Ш.К.А", "Малая.", "СrAzy_Girl", "ТушкАНчик", "Э̶d̶e̶f̶ ℓo√ﻉ~", "ЯдовитаЯ", "[ღ gadkii _ya ღ]", "*... а глазки то блестят у...*", "*superSKAya*", "=}ОфИгЕнНаЯ{=", "ANIGAKEKY", "ArinaVerba", "besenok", "best in any directions", "BoSoranY (калдунья)", "bulka", "cheese-ловкаМ", "chik_in_love", "Dasna Fox", "Dauf", "DEWCA", "Eclipse of the heart", "Envy Angel", "Firm_ice", "gidra", "I.love.you", "ir1shk@", "KaraMbitka@", "kika", "Kotuk Global", "Kriatall", "KunьKa", "Lapka_play", "Lichay", "Lizi|Fox", "Luffla", "Mare", "Mati0xxx", "Miss-l-КaRaлEва", "Mыl_l_loHok-", "N@F@NY@", "only_you", "OPIMA", "playful", "Princessa Aiwa", "PYLIZOCIHA", "real imp", "SheriLady", "SmilyFace", "Tanchik", "tenderness", "THE COffeine", "WingS_oF_ButterFly", "Yana_Pilnik", "Your love-my medicine", "блонда", "Богиня_Флорка", "МаLыШkA", "Магичeckaя", "нюня", "Сильная девочка", "Скромняшка", "такая как есть", "Убeй _Meня_ Heжнo", "фигася", "Элрийна", "˙˙·٠•●S4@sТLиVый _ ]{оLоб4Ён]{@●•٠·˙˙", "*SYPERmenka*", "=LyuBima[Ya]` ZaEchkA=", "Bad Girls", "bonny", "Cut Girl", "Cплиня", "Dαrκ Quεεη", "Drew88888", "EJEFEJAKA", "elis", "EVA KISS", "Ggbitka", "Hellspuffs", "idi vori", "iGirl", "KaPlYa", "KARALEVA", "KIOSHIMA",
        "manyaka", "Mimichka", "MissKatia", "MissSpeed", "miss_letto", "Mrs ZERO", "oTTaco4ka", "OZYZABY", "PionerAN", "pozitiff4ik", "PRoMo present...", "Pumpa ", "rainbow petting", "Raqway", "Referator", "RODA", "Roksana", "RYHYNYMEG", "StarMoon", "suyeta_ona", "sweet dreams", "SYN", "tastie_of_live", "TEFAXOMITA", "TOpaVAi_4iKA", "TOY", "Twilight", "ValetinsDay", "Vika_Pro", "Whiskas_meow_meow", "WOFAXELA", "your weakness", "Zacharovannaya Зачарованная", "Анастейша Стил", "Ангелочек твой", "Бака", "Бауэр", "бомбаракета", "Ветерок", "Вика Крист", "виртуалка", "Вишенка слаймы", "ВРеднaia", "гордая", "даже не мечтайте", "дарья класс", "Девочка твоей мечты", "Дер_Зкая", "дИАН", "Для Своих Своя", "Зая но не твоЯ", "Ирина", "Йа Твоя На Веки", "камила", "Клубника", "кобра", "кокетка", "Конфетк@", "краснаяРоза", "Кристинка Оставь", "ксения", "Ксюю", "Лена", "ли_Н_за", "любима тобой", "Маленькая Мисс", "МаРиНа", "Мего Деффочка", "Миss Капpиз", "Милафа", "милафка", "МиссЕнотик", "Недатрога", "нежность", "неотразимая", "ника", "ОЛЯЛЮНЬКА", "Отверженная", "открой глаза", "П☈☉кλяTаЯ р@Ем", "Похитила твой сердце", "пошлЯдка", "Просто Девочка!", "сolour_dream", "серая королева", "СеРоГлАзАя ЗаЯ", "сумашедшая", "ТβоЯзAя", "твое солнце", "Твоя зая", "урка", "усем_на_радость", "чертонок в кедах", "Чууудик", "Щёчка Лоуренс", "это сильнее меня", "Э|Јеk@", "Я ещё маленькая", "я ЛюБоВь ТвОя", "язва", "Я_Люблю_Аниме", "Я_Люблю_Печенье", "Я_Люблю_Шоколад", "[... maDama ...]", "[L]ady☆[M]iller", "[АЦЦкАя_КаНХвЕтА]", "[Дом666]", "_КраБик_", "_НАДОело_жить_: *_я_шикарна_*", "Ҡợ℘αሎ", "Íḿρěřâṫřίĉâ", "猫burst angel返", "Şpìlką", "( ДеВчУшКи )", "...Капууууста..", "40 размер груди", "4oKnUTaYa", "??SummerTime??", "alexis_koks", "ASALI", "Bayura", "blue horse", "Crazy GirL", "DaRiNkA", "Desembra", "DodyGirl", "Evaina", "gnobaaa", "ice_queen", "ideal_girl", "IIIKOJLHA9", "kanpu[zZz]ka", "Kasumi", "Katana", "Kitsune May", "KR[a]S[a]VITS[a]", "KykLa", "Lasha", "Lazy Owl", "light bite", "Lost time", "memorable", "mi-mi=)", "miracle", "MoonFairy", "Mrs.Smitty", "norvegian_candy", "NOWEBYLAV", "Olga", "only me", "Only time can tell", "Oчaroвательnaя Дevoчка", "Pretty ", "Princess Bun", "princess_of_forest", "Promo Girl", "SAQENA", "seksistar", "sunhills", "Sun_beam", "SТервозная", "The Princess and the pea", "your own personal brand of heroin", "yummy lips", "Аватприя", "Анимешная", "богиня", "Был бы смелее была бы твоею", "ВаТуСьКа", "дракоша", "Йя_Сама_ТаКая_", "кукушка", "маКатя", "Маленькая Стервочка", "Мальнекая принцесса", "мЕго-цыпа", "Мелодия", "Мурка", "Мятный чаечек", "Настена", "невозвратимая", "ПЕтр_УхО_о", "ПиРоЖоК", "Сахарок Тян", "Твоя-Нежная-Фея-Из-Маленькой-Детской-Мечты", "Экстремальная", "Я твоя мечта", "[GTA MAN]", "ÅÑGÈLØK", "ᎪḞᏒᎾƊĨᎢᎪ", "***мармеладка-шоколадка***", "**Pafos", "*...Лю_ЕгО_оЧ...*", "---->жизнь удалась", "...Feel that I love...", "..:::КнОпосьКа", "A-lisA", "All sweet one", "Alpinistka", "AmeliaMorrigan", "annaleto", "ATYKAY", "awon", "Belly", "Brusnika", "deep_eyes", "ELI", "flower of love", "Hazel", "Hlamina", "Holymage", "Ido", "Insecta", "IOU", "KaRaKakashka", "KARAPUZICHEK**LOVE***", "KaRoLine", "Kivi_Swoy", "KrasotcaKatya", "Krasotochka", "Læðy", "Lana", "Lanas", "Lilpanda", "MakeHappy", "MurMuring", "MUu_хА", "NаГлаYA", "pachooley", "Poison of the souL", "queen of beauty", "rakasa", "Rebel", "Stanley Mathers", "Studentka", "Superхрэн", "ta_ewe_4tucka", "There might be thy name", "the_sweet_kiss", "tiffani", "Vasilisa V KeDax", "Yamshikova", "АМЕРИКАНКА", "Амурчик", "аустома", "Верочка", "Девочка-скандал", "диана", "ЖиРнаЯ_БрЮЗга", "Зайка ПуФФ", "Злоба`", "Карандашница", "Крошка Енот", "Маленькая капризка", "МаЛыФка^_^", "Мне по КАЙФУ", "Морковка", "Мэйкмисс", "Невеста ветра", "Неженка", "Поцелуй дождЯ", "просковья_из помосковья", "пупырышек", "Ревнивая", "Ромашка", "Сирена", "стерва_sskontakte]", "Супер Настя", "шпунька", "¯̿ ̿|̿ ̿ |̶ ̶ ̶ ̶| |̶͇̿ ̶͇̿ ͇̿_", "♚Теперь☛[блОнд]☚иНочк∀♚", "**Innushka**", "-ДикаЯ-", ".::ЙоПт", "0SuNnY GiRl0", ">>Кассиус Клей<<", "?Colour_dream?", "?БлондинкО в шОкОладе?", "Beatiful dream", "believing_me", "Blond Owl", "Brunetochka_v_zefire", "Cold◊heart~]TM", "dared to try", "EvilGirl", "Fantamasik@**", "fito_nyasha", "Friendly", "gera", "giona", "girona", "goril-vanil", "Herobrina", "hloya", "Honda", "Kapystochka!)", "kite", "Klassta", "Koketka", "krasotulechka", "lady of passion", "laruta",
        "LediK", "lioness", "lonik", "Maneater", "mrs_dementia", "Nature queen", "NUOSAVA", "olasasha", "Oreya", "pink angel", "RemarkA", "Resa", "sky_bird", "SladkaЯ", "syn_dream", "undermoon", "unruly", "Velvet Dream", "WULAVOCOLZ", "Your mistakeS", "you_love", "ZUROWA", "Аглая", "Ало-белая Роза", "Альфика", "АПАСНАЯ", "Афродиттта", "Ацкий_рЫжеГГ", "Беруша", "Бетси", "Варенчик**=)", "Ведьмочка", "ВесельЧайка", "Винø", "Вишенка", "ВЛюбви", "ВоПирШа", "Гламурка", "Готесска", "ГРЕКИНА", "ДженфирКО", "Дитя природы", "Его зайка", "ИзУмИтЕ1нАЯ", "ИЗумРУДная", "ИНДИВА", "КаракулЯ", "Карамболька", "Китикетка", "КнОпосьКа", "колбасяха", "Красотка", "Красоткин", "КрАсоТоЧка", "КровьРозы", "Крутая чела", "Крутышка", "Круче Gucci", "Кукольная внешность", "КукуЗика<3", "Литота", "Лунат", "Люби меня как я тебя", "Любитeльница_кeкcoв", "ЛюБлЮ сЕрДЦем", "Машашка", "Мего Пчёлка_", "Милаха", "Милый", "Мулатка-Шоколадка", "Наглая", "НАКОВАЛЬНЯ", "Находка14 ", "Не скромное солнце", "Незнакомка", "Неужели это я", "Нивада", "Осонирда", "Парадигма", "Подруга Бонда", "Полярная ночь", "ПОМПОН", "Просто его счастье", "ПсЫхопадГO_o", "ПуПиРиШкА", "Пупсик", "ПЧЁЛА", "Рыбка2014", "СВОБОДНАЯ", "сердечки", "СеРдЦе БьЕтСя", "Симпатуля", "Скрип-стрип", "Скромница", "Сладкий_KiSs", "Слёзы осени", "Сливочка", "Страстная богиня", "Счастье твое", "Счастье_снова_в_модЕ", "Твое солнце", "твоя девочка)", "Твоя королева", "ТвОя МаРмЕлАдКа", "Труженица Советского Союза", "Туточка", "УЖЕ НЕ ТВОЯ", "Улыбчивая", "Утанувшая в его руках", "ХоХоТуНчИк", "Хто йа?", "[¦?B@D GirL?¦]", " HARD_girl", "(45½ Человека)", "???Мрр....???", "adela", "Enotka", "EvelinA", "Lady☆Miller", "Life_IS_Life", "Lioniya", "LIZZKA", "Loony", "Miss Princesa", "MissKissyCat", "Mrrr_Kowka", "romantic_girl", "soledad", "wonder woman", "Zooo_Show", "ZUHA", "Бабочка без крыльев", "Белая Лебедь", "БЕссмертнаябестИЯ", "Б_о_с_о_н_о_ж_к_а", "Клубный ребёнок^_^", "Колючка", "красопета", "ЛЕНЧАНОЧКА", "Милашка с карими глазками", "ПолькО Необычная", "Про100 я", "Реверсина", "СИБИРЯЧКА Я", "Силоша", "Твой Рай", "Умни4к@", "ФоНтИк", "^^Че АрЕшЬ^^", "_MentoS_", "+++C I S S E # 9 +++", ";-(All sweet one)-;", "Alenyshkaklas", "B-a-r-b-u", "beatiful_wife", "Black Dog", "Chumachechya LiSa", "Dawn Princess", "flying_for_you", "fufA", "GodLovesMe", "Hatty Brutallity", "KULESHIK^SASHYL}{A**", "KySaI•LoKtI", "Mania-X", "Marialla", "Mazie", "mili", "NeTvoyaKrasotka", "PEPYSA", "PuFig", "sully", "TAROTEAPE", "Аскарбика", "Блистательница", "Владилен Кэт", "деLOVEаЙА", "ДЕВКА ВЕСНА", "Его [ЛЮБИМАЯ] Заечка", "З_а_я++Р_а_я", "Йа_не_леди_йа_отморозок", "КрадуУ_ДушИ_ДорогОо", "Леди Кукуруза", "Ликаста", "МИСС ВСЕЛЕННАЯ", "Молекулка000", "Мэрэлинн", "Накундила", "Самая кайфовая девочка", "Стихия", "Твоя жизнь", "Ты мой ПриНц", "Убивашка", "ЭйфориАнна", "Юшка", "*(=ОлькО_DеткО=)*", ". ·˙˙⋆♥ Solodenka ♥⋆˙˙· .", "3ABuCTb", "I will deceive you cruelly", "Nobody", "Quiqlyrabbitt", "Waina", "Безутешная вдова", "Изюминка", "Мстящая за котика", "Прохиндейка", "Сладенькая", "Химия`", "Черная (белая) полоса твоя", "+ЦаРиЦа+", "Lololowka", "VioleT", "Белка", "Важная", "Девонька", "ЕгО гЛуПыШкА", "Малыш Гашиш", "Монстер", "Супер я", "Зая из рая)", "Кристина Агилеровна", "Angel on a cloud", "Магма", "succubus", "Девушка Киллер",
    ],
];
//время создания боя
$battle_start_time = time();
//разница в уровне
$level_incdec = 3;
//время ожидания
$time_wait = 5;
//время через которое игроки смогут снова создать бой друг с другом
$time_out_hero = time() + 600;
//количество присоединяемых ботов если героев нету
$botNum = 1;

//получаем всех игроков зарегистрированых на 1_1 дуэль
$arrAllRes = $mc->query("SELECT * FROM `huntb_list` WHERE `type`='1' || `type`='2'");
if ($arrAllRes->num_rows > 0) {
    //достаем по одному всех зарегистрированых
    while ($arrNext = $arrAllRes->fetch_array(MYSQLI_ASSOC)) {
        $arrNextThisRes = $mc->query("SELECT * FROM `huntb_list` WHERE `user_id` = '" . $arrNext['user_id'] . "'&& `type`='" . $arrNext['type'] . "'");
        if ($arrNextThisRes->num_rows > 0) {
            $arrNextThis = $arrNextThisRes->fetch_array(MYSQLI_ASSOC);
            $userRes = $mc->query("SELECT * FROM `users` WHERE `id` = '" . $arrNextThis['user_id'] . "' && `temp_health`>'0' ");
            if ($userRes->num_rows > 0) {
                $user = $userRes->fetch_array(MYSQLI_ASSOC);
                //айди боя
                $battle_id = rand(0, time()) . rand(0, time()) . rand(0, time());
                if ($user['side'] == 0 || $user['side'] == 1) {
                    $command1 = $user_command = 0;
                    $command2 = 1;
                } else {
                    $command1 = $user_command = 1;
                    $command2 = 0;
                }
                //получаем противника который из другой команды , которого нет в ранее битых
                $arrMRes = $mc->query("SELECT * FROM `users` WHERE `id` IN (SELECT `user_id` FROM `huntb_list` WHERE `rasa` != '$user_command' && `level` >= '" . ($user['level'] - $level_incdec) . "' && `level` <= '" . ($user['level'] + $level_incdec) . "'  && `type`='" . $arrNextThis['type'] . "' && `user_id` NOT IN (SELECT `id_user2` FROM `response` WHERE `id_user1` = '" . $user['id'] . "')) && `id` != '" . $user['id'] . "' && `temp_health`>'0'  LIMIT 1");
                if ($arrMRes->num_rows > 0) {
                    $arrM = $arrMRes->fetch_array(MYSQLI_ASSOC);
                    $user2 = $mc->query("SELECT * FROM `users` WHERE `id` = '" . $arrM['id'] . "'")->fetch_array(MYSQLI_ASSOC);
                    hero1_add($command1, $user, $user2['name'], $battle_id, $battle_start_time, $arrNextThis['type']);
                    hero2_add($command2, $user2, $battle_id, $battle_start_time, $arrNextThis['type']);
                    $control0 = $mc->query("DELETE FROM `huntb_list` WHERE `user_id` = '" . $user['id'] . "'");
                    $control1 = $mc->query("DELETE FROM `huntb_list` WHERE `user_id` = '" . $arrM['id'] . "'");
                    $control3 = $mc->query("INSERT INTO `response`(`id`, `id_user1`, `id_user2`, `time_start`, `type`) VALUES ('NULL','" . $user['id'] . "','" . $arrM['id'] . "','$time_out_hero','" . $arrNextThis['type'] . "')");
                    $control4 = $mc->query("INSERT INTO `response`(`id`, `id_user1`, `id_user2`, `time_start`, `type`) VALUES ('NULL','" . $arrM['id'] . "','" . $user['id'] . "','$time_out_hero','" . $arrNextThis['type'] . "')");
                } else if ($arrNextThis['time_start'] + $time_wait < time()) {
                    $arrbotpar = genbotpar($command2, $names, $botNum);
                    hero1_add($command1, $user, $arrbotpar[0][0] . "[БОТ]", $battle_id, $battle_start_time, $arrNextThis['type']);
                    for ($i = 0; $i < count($arrbotpar[0]); $i++) {
                        bot_add($arrbotpar[0][$i] . "[БОТ]", $command2, $arrbotpar[1][$i], $arrbotpar[2][$i], $user['level'], $battle_id, $battle_start_time, $arrNextThis['type']);
                    }
                    $control2 = $mc->query("DELETE FROM `huntb_list` WHERE `user_id` = '" . $user['id'] . "'");
                }
            }
        }
    }
}

function genbotpar($command2, $names, $z) {
    $n = [];
    $si = [];
    $st = [];
    $i = 0;
    while ($i < $z) {
        if ($command2 == 1) {
            $side = rand(2, 3);
        } else if ($command2 == 0) {
            $side = rand(0, 1);
        }
        if ($side == 0 || $side == 2) {
            $pol = 0;
        } else if ($side == 1 || $side == 3) {
            $pol = 1;
        }
        $temp = $names[$pol][array_rand($names[$pol])];
        if (!in_array($temp, $n)) {
            $n[] = $temp;
            $si[] = $side;
            $st[] = rand(0, 4);
            $i++;
        }
    }
    return [$n, $si, $st];
}

function hero1_add($command, $user, $user2_name, $battle_id, $battle_start_time, $type) {
    global $mc;
    $arr1 = [[], []];
    $shops_ids = [];
    $PA = $user;
    $PA['weaponico'] = 0;
    $PA['Pshieldnum'] = 0;
    $PweaponEffect = array();

    //pl 1
    $arr1 = [];
    $arr1['temp_health'] = $PA['temp_health'];
    $arr1['max_health'] = $PA['health'];
    $arr1['strength'] = $PA['strength'];
    $arr1['toch'] = $PA['toch'];
    $arr1['lov'] = $PA['lov'];
    $arr1['kd'] = $PA['kd'];
    $arr1['block'] = $PA['block'];
    $arr1['bron'] = $PA['bron'];
    //пересчет параметров игрока
    //получаем список одетых вещей героя
    $result221 = $mc->query("SELECT * FROM `userbag` WHERE `id_user` = '" . $PA['id'] . "' && `dress`='1' && `BattleFlag`='1' || `id_user` = '" . $PA['id'] . "' AND `id_punct`>'9' && `BattleFlag`='1'");
    $myrow221 = $result221->fetch_all(MYSQLI_ASSOC);
    //перебираем параметры вещей

    for ($i = 0; $i < count($myrow221); $i++) {
        //read thing
        $result1 = $mc->query("SELECT * FROM `shop` WHERE `id`='" . $myrow221[$i]['id_shop'] . "'");
        if ($result1->num_rows > 0) {
            //thing to arr par
            $infoshop = $result1->fetch_array(MYSQLI_ASSOC);
            $shops_ids[] = [addslashes($infoshop['name']), $infoshop['id']];
            $arr1['max_health'] += $infoshop['health'];
            $arr1['strength'] += $infoshop['strength'];
            $arr1['toch'] += $infoshop['toch'];
            $arr1['lov'] += $infoshop['lov'];
            $arr1['kd'] += $infoshop['kd'];
            $arr1['block'] += $infoshop['block'];
            $arr1['bron'] += $infoshop['bron'];
            //переводим в иконку оружия
            if ((int) $infoshop['id_punct'] == 1) {
                if ($infoshop['id_image'] <= 36 || $infoshop['id_image'] >= 279 && $infoshop['id_image'] <= 298) {
                    $PA['weaponico'] = $infoshop['id_image'];
                } else {
                    $PA['weaponico'] = 0;
                }
            }
            //получаем количество щита
            if ((int) $infoshop['id_punct'] == 2) {
                $PA['Pshieldnum'] = $infoshop['koll'];
            }
            if ($PA['stil'] >= 0 && $PA['stil'] < 5) {
                //запись эффектов оружия
                if (is_array(json_decode_nice($infoshop['effects']))) {
                    $PweaponEffect = array_merge($PweaponEffect, json_decode_nice($infoshop['effects']));
                }
            }
        }
    }
    $mc->query("INSERT INTO`battle`"
            . "("
            . "`id`,"
            . "`Pname`,"
            . "`Pnamevs`,"
            . "`Pvsname`,"
            . "`level`,"
            . "`Pico`,"
            . "`Pflife`,"
            . "`Plife` ,"
            . "`Ptochnost`,"
            . "`Pblock`,"
            . "`Puron`,"
            . "`Pbronia`,"
            . "`Poglushenie`,"
            . "`Puvorot`,"
            . "`Pweaponico`,"
            . "`Pshieldnum`,"
            . "`Pshieldonoff`,"
            . "`Ptype`,"
            . "`Pvisible`,"
            . "`Mvisible`,"
            . "`Panimation`,"
            . "`Manimation`,"
            . "`Phod`,"
            . "`Phodtime`,"
            . "`Pauto`,"
            . "`PAlwaysEffect`,"
            . "`PeleksirVisible`,"
            . "`PweaponEffect`,"
            . "`PentityEffect`,"
            . "`MentityEffect`,"
            . "`super`,"
            . "`Mid`,"
            . "`location`,"
            . "`type_battle`,"
            . "`battle_id`,"
            . "`battle_start_time`,"
            . "`command`,"
            . "`lost_mob_id`,"
            . "`player_activ`,"
            . "`end_battle`,"
            . "`counter`,"
            . "`stil`,"
            . "`shops_ids`"
            . ")VALUES("
            . "NULL,"
            . "'" . $PA['name'] . "',"
            . "'" . $PA['name'] . "',"
            . "'$user2_name',"
            . "'" . $PA['level'] . "',"
            . "'" . $PA['side'] . "',"
            . "'" . $arr1['max_health'] . "',"
            . "'" . $PA['temp_health'] . "',"
            . "'" . $arr1['toch'] . "',"
            . "'" . $arr1['block'] . "',"
            . "'" . $arr1['strength'] . "',"
            . "'" . $arr1['bron'] . "',"
            . "'" . $arr1['kd'] . "',"
            . "'" . $arr1['lov'] . "',"
            . "'" . $PA['weaponico'] . "',"
            . "'" . $PA['Pshieldnum'] . "',"
            . "'0',"
            . "'0',"
            . "'1',"
            . "'1',"
            . "'0',"
            . "'0',"
            . "'1',"
            . "'" . time() . "',"
            . "'0',"
            . "'[]',"
            . "'1',"
            . "'" . json_encode($PweaponEffect) . "',"
            . "'[]',"
            . "'[]',"
            . "'" . $PA['superudar'] . "',"
            . "'" . $PA['id'] . "',"
            . "'" . $PA['location'] . "',"
            . "'$type',"
            . "'" . $battle_id . "',"
            . "'" . $battle_start_time . "',"
            . "'" . $command . "',"
            . "'0',"
            . "'1',"
            . "'0',"
            . "'0',"
            . "'" . $PA['stil'] . "',"
            . "'" . json_encode($shops_ids, JSON_UNESCAPED_UNICODE) . "'"
            . ")");
}

function hero2_add($command, $user, $battle_id, $battle_start_time, $type) {
    global $mc;
    $arr2 = [[], []];
    $shops_ids = [];
    $MA = $user;
    $MA['weaponico'] = 0;
    $MA['Pshieldnum'] = 0;
    $MPweaponEffect = array();

    //pl 2
    $arr2 = [];
    $arr2['temp_health'] = $MA['temp_health'];
    $arr2['max_health'] = $MA['health'];
    $arr2['strength'] = $MA['strength'];
    $arr2['toch'] = $MA['toch'];
    $arr2['lov'] = $MA['lov'];
    $arr2['kd'] = $MA['kd'];
    $arr2['block'] = $MA['block'];
    $arr2['bron'] = $MA['bron'];
    //пересчет параметров игрока
    //получаем список одетых вещей героя
    $result221 = $mc->query("SELECT * FROM `userbag` WHERE `id_user` = '" . $MA['id'] . "' AND `dress`='1' && `BattleFlag`='1' || `id_user` = '" . $MA['id'] . "' AND `id_punct`>'9' && `BattleFlag`='1'");
    $myrow221 = $result221->fetch_all(MYSQLI_ASSOC);
    //перебираем параметры вещей

    for ($i = 0; $i < count($myrow221); $i++) {
        //read thing
        $result1 = $mc->query("SELECT * FROM `shop` WHERE `id`='" . $myrow221[$i]['id_shop'] . "'");
        if ($result1->num_rows > 0) {
            //thing to arr par
            $infoshop = $result1->fetch_array(MYSQLI_ASSOC);
            $shops_ids[] = [addslashes($infoshop['name']), $infoshop['id']];
            $arr2['max_health'] += $infoshop['health'];
            $arr2['strength'] += $infoshop['strength'];
            $arr2['toch'] += $infoshop['toch'];
            $arr2['lov'] += $infoshop['lov'];
            $arr2['kd'] += $infoshop['kd'];
            $arr2['block'] += $infoshop['block'];
            $arr2['bron'] += $infoshop['bron'];
            //переводим в иконку оружия
            if ((int) $infoshop['id_punct'] == 1) {
                if ($infoshop['id_image'] <= 36 || $infoshop['id_image'] >= 279 && $infoshop['id_image'] <= 298) {
                    $MA['weaponico'] = $infoshop['id_image'];
                } else {
                    $MA['weaponico'] = 0;
                }
            }
            //получаем количество щита
            if ((int) $infoshop['id_punct'] == 2) {
                $MA['Pshieldnum'] = $infoshop['koll'];
            }
            if ($MA['stil'] >= 0 && $MA['stil'] < 5) {
                //запись эффектов оружия
                if (is_array(json_decode_nice($infoshop['effects']))) {
                    $MPweaponEffect = array_merge($MPweaponEffect, json_decode_nice($infoshop['effects']));
                }
            }
        }
    }
    $mc->query("INSERT INTO`battle`"
            . "("
            . "`id`,"
            . "`Pname`,"
            . "`Pnamevs`,"
            . "`Pvsname`,"
            . "`level`,"
            . "`Pico`,"
            . "`Pflife`,"
            . "`Plife` ,"
            . "`Ptochnost`,"
            . "`Pblock`,"
            . "`Puron`,"
            . "`Pbronia`,"
            . "`Poglushenie`,"
            . "`Puvorot`,"
            . "`Pweaponico`,"
            . "`Pshieldnum`,"
            . "`Pshieldonoff`,"
            . "`Ptype`,"
            . "`Pvisible`,"
            . "`Mvisible`,"
            . "`Panimation`,"
            . "`Manimation`,"
            . "`Phod`,"
            . "`Phodtime`,"
            . "`Pauto`,"
            . "`PAlwaysEffect`,"
            . "`PeleksirVisible`,"
            . "`PweaponEffect`,"
            . "`PentityEffect`,"
            . "`MentityEffect`,"
            . "`super`,"
            . "`Mid`,"
            . "`location`,"
            . "`type_battle`,"
            . "`battle_id`,"
            . "`battle_start_time`,"
            . "`command`,"
            . "`lost_mob_id`,"
            . "`player_activ`,"
            . "`end_battle`,"
            . "`counter`,"
            . "`stil`,"
            . "`shops_ids`"
            . ")VALUES("
            . "NULL,"
            . "'" . $MA['name'] . "',"
            . "'',"
            . "'',"
            . "'" . $MA['level'] . "',"
            . "'" . $MA['side'] . "',"
            . "'" . $arr2['max_health'] . "',"
            . "'" . $MA['temp_health'] . "',"
            . "'" . $arr2['toch'] . "',"
            . "'" . $arr2['block'] . "',"
            . "'" . $arr2['strength'] . "',"
            . "'" . $arr2['bron'] . "',"
            . "'" . $arr2['kd'] . "',"
            . "'" . $arr2['lov'] . "',"
            . "'" . $MA['weaponico'] . "',"
            . "'" . $MA['Pshieldnum'] . "',"
            . "'0',"
            . "'0',"
            . "'1',"
            . "'1',"
            . "'0',"
            . "'0',"
            . "'0',"
            . "'" . time() . "',"
            . "'0',"
            . "'[]',"
            . "'1',"
            . "'" . json_encode($MPweaponEffect) . "',"
            . "'[]',"
            . "'[]',"
            . "'" . $MA['superudar'] . "',"
            . "'" . $MA['id'] . "',"
            . "'" . $MA['location'] . "',"
            . "'$type',"
            . "'" . $battle_id . "',"
            . "'" . $battle_start_time . "',"
            . "'" . $command . "',"
            . "'0',"
            . "'1',"
            . "'0',"
            . "'0',"
            . "'" . $MA['stil'] . "',"
            . "'" . json_encode($shops_ids, JSON_UNESCAPED_UNICODE) . "'"
            . ")");
}

function bot_add($name, $command, $side, $stil, $level, $battle_id, $battle_start_time, $type) {
    global $mc;
    $arr2 = [];
    $arr2['weaponico'] = 0;
    $arr2['Pshieldnum'] = 0;
    $shops_ids = [];
    $arrSuperLevel = [
        "",
        "",
        "22",
        "22",
        "22,222",
        "22,222",
        "22,222",
        "22,222",
        "22,222,2222",
        "22,222,2222",
        "22,222,2222",
        "22,222,2222",
        "22,222,2222",
        "22,222,2222",
        "22,222,2222",
        "22,222,2222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
        "22,222,2222,222222",
    ];
    $arr2['superudar'] = $arrSuperLevel[$level];
    $arr2['max_health'] = 10 + (5 * $level);
    $arr2['strength'] = 1 + (2 * $level) - 2;
    $arr2['toch'] = 8 + (2 * $level) - 2;
    $arr2['lov'] = 3 + (2 * $level) - 2;
    $arr2['kd'] = 2 + (2 * $level) - 2;
    $arr2['block'] = 0 + (2 * $level) - 2;
    $arr2['bron'] = 0 + (2 * $level) - 2;

    $MPweaponEffect = array();
    if ($level > 20) {
        $arrcount = [
            1, 2, 3, 4, 5, 6, 7,
            8, 8,
            9, 9, 9, 9,
            10, 10, 10, 10,
            11, 11, 11, 11
        ];
    } else if ($level > 14) {
        $arrcount = [
            1, 2, 3, 4, 5, 6, 7,
            8, 8,
            9, 9, 9,
            10, 10, 10,
            11, 11, 11
        ];
    } else if ($level > 11) {
        $arrcount = [
            1, 2, 3, 4, 5, 6, 7,
            8, 8,
            9, 9,
            10, 10,
            11, 11
        ];
    } else if ($level > 10) {
        $arrcount = [
            1, 2, 3, 4, 5, 6, 7,
            8, 8,
            9, 9,
            10, 10
        ];
    } else if ($level > 9) {
        $arrcount = [
            1, 2, 3, 4, 5, 6, 7,
            8, 8,
            9, 9,
        ];
    } else {
        $arrcount = [
            1, 2, 3, 4, 5, 6, 7,
            8, 8
        ];
    }
    if ($level > 20) {
        $minlvl = 10;
    } else if ($level > 19) {
        $minlvl = 10;
    } else if ($level > 18) {
        $minlvl = 10;
    } else if ($level > 17) {
        $minlvl = 10;
    } else if ($level > 16) {
        $minlvl = 10;
    } else if ($level > 15) {
        $minlvl = 10;
    } else if ($level > 14) {
        $minlvl = 5;
    } else if ($level > 13) {
        $minlvl = 5;
    } else if ($level > 12) {
        $minlvl = 5;
    } else if ($level > 11) {
        $minlvl = 5;
    } else if ($level > 10) {
        $minlvl = 5;
    } else if ($level > 9) {
        $minlvl = 0;
    } else if ($level > 8) {
        $minlvl = 0;
    } else if ($level > 7) {
        $minlvl = 0;
    } else if ($level > 6) {
        $minlvl = 0;
    } else if ($level > 5) {
        $minlvl = 0;
    } else if ($level > 4) {
        $minlvl = 0;
    } else {
        $minlvl = 0;
    }
    //получение случайных вещей бота
    $myrow221 = [];
    for ($i = 0; $i < count($arrcount); $i++) {
        if ($rndc = rand(0, $mc->query("SELECT * FROM `shop` WHERE `id_punct` = '$arrcount[$i]' && `BattleFlag`='1' && `stil` = '$stil' && `level` <= '$level' && `level` > '$minlvl' && `id` IN (SELECT `id_shop` FROM `shop_equip` WHERE `id_location`!='0' && `id_location`!='23' GROUP BY `id_shop`)")->num_rows)) {
            $tmpRes0 = $mc->query("SELECT * FROM `shop` WHERE `id_punct` = '$arrcount[$i]' && `BattleFlag`='1' && `stil` = '$stil' && `level` <= '$level' && `level` > '$minlvl' && `id` IN (SELECT `id_shop` FROM `shop_equip` WHERE `id_location`!='0' && `id_location`!='23' GROUP BY `id_shop`) ORDER BY `level` ASC LIMIT " . $rndc . ",1");
            if ($tmpRes0->num_rows > 0) {
                $myrow221 [] = $tmpRes0->fetch_array(MYSQLI_ASSOC);
            }
        } else if ($rndc = rand(0, $mc->query("SELECT * FROM `shop` WHERE `id_punct` = '$arrcount[$i]' && `BattleFlag`='1' && `stil` = '0' && `level` <= '$level' && `level` > '$minlvl' && `id` IN (SELECT `id_shop` FROM `shop_equip` WHERE `id_location`!='0' && `id_location`!='23' GROUP BY `id_shop`)")->num_rows)) {
            $tmpRes1 = $mc->query("SELECT * FROM `shop` WHERE `id_punct` = '$arrcount[$i]' && `BattleFlag`='1' && `stil` = '0' && `level` <= '$level'  && `level` > '$minlvl' && `id` IN (SELECT `id_shop` FROM `shop_equip` WHERE `id_location`!='0' && `id_location`!='23' GROUP BY `id_shop`) ORDER BY `level` ASC LIMIT " . $rndc . ",1");
            if ($tmpRes1->num_rows > 0) {
                $myrow221 [] = $tmpRes1->fetch_array(MYSQLI_ASSOC);
            }
        }
    }
    //перебираем параметры вещей
    for ($i = 0; $i < count($myrow221); $i++) {
        $shops_ids[] = [addslashes($myrow221[$i]['name']), $myrow221[$i]['id']];
        $arr2['max_health'] += $myrow221[$i]['health'];
        $arr2['strength'] += $myrow221[$i]['strength'];
        $arr2['toch'] += $myrow221[$i]['toch'];
        $arr2['lov'] += $myrow221[$i]['lov'];
        $arr2['kd'] += $myrow221[$i]['kd'];
        $arr2['block'] += $myrow221[$i]['block'];
        $arr2['bron'] += $myrow221[$i]['bron'];
        //переводим в иконку оружия
        if ((int) $myrow221[$i]['id_punct'] == 1) {
            if ($myrow221[$i]['id_image'] <= 36 || $myrow221[$i]['id_image'] >= 279 && $myrow221[$i]['id_image'] <= 298) {
                $arr2['weaponico'] = $myrow221[$i]['id_image'];
            } else {
                $arr2['weaponico'] = 0;
            }
        }
        //получаем количество щита
        if ((int) $myrow221[$i]['id_punct'] == 2) {
            $arr2['Pshieldnum'] = $myrow221[$i]['koll'];
        }
        if ($stil >= 0 && $stil < 5) {
            //запись эффектов оружия
            if (is_array(json_decode_nice($myrow221[$i]['effects']))) {
                $MPweaponEffect = array_merge($MPweaponEffect, json_decode_nice($myrow221[$i]['effects']));
            }
        }
    }
    $mc->query("INSERT INTO`battle`"
            . "("
            . "`id`,"
            . "`Pname`,"
            . "`Pnamevs`,"
            . "`Pvsname`,"
            . "`level`,"
            . "`Pico`,"
            . "`Pflife`,"
            . "`Plife` ,"
            . "`Ptochnost`,"
            . "`Pblock`,"
            . "`Puron`,"
            . "`Pbronia`,"
            . "`Poglushenie`,"
            . "`Puvorot`,"
            . "`Pweaponico`,"
            . "`Pshieldnum`,"
            . "`Pshieldonoff`,"
            . "`Ptype`,"
            . "`Pvisible`,"
            . "`Mvisible`,"
            . "`Panimation`,"
            . "`Manimation`,"
            . "`Phod`,"
            . "`Phodtime`,"
            . "`Pauto`,"
            . "`PAlwaysEffect`,"
            . "`PeleksirVisible`,"
            . "`PweaponEffect`,"
            . "`PentityEffect`,"
            . "`MentityEffect`,"
            . "`super`,"
            . "`Mid`,"
            . "`location`,"
            . "`type_battle`,"
            . "`battle_id`,"
            . "`battle_start_time`,"
            . "`command`,"
            . "`lost_mob_id`,"
            . "`player_activ`,"
            . "`end_battle`,"
            . "`counter`,"
            . "`stil`,"
            . "`shops_ids`"
            . ")VALUES("
            . "NULL,"
            . "'$name',"
            . "'',"
            . "'',"
            . "'$level',"
            . "'$side',"
            . "'" . $arr2['max_health'] . "',"
            . "'" . $arr2['max_health'] . "',"
            . "'" . $arr2['toch'] . "',"
            . "'" . $arr2['block'] . "',"
            . "'" . $arr2['strength'] . "',"
            . "'" . $arr2['bron'] . "',"
            . "'" . $arr2['kd'] . "',"
            . "'" . $arr2['lov'] . "',"
            . "'" . $arr2['weaponico'] . "',"
            . "'" . $arr2['Pshieldnum'] . "',"
            . "'0',"
            . "'0',"
            . "'1',"
            . "'1',"
            . "'0',"
            . "'0',"
            . "'0',"
            . "'" . time() . "',"
            . "'1',"
            . "'[]',"
            . "'1',"
            . "'" . json_encode($MPweaponEffect) . "',"
            . "'[]',"
            . "'[]',"
            . "'" . $arr2['superudar'] . "',"
            . "'-1',"
            . "'0',"
            . "'$type',"
            . "'" . $battle_id . "',"
            . "'" . $battle_start_time . "',"
            . "'" . $command . "',"
            . "'0',"
            . "'1',"
            . "'0',"
            . "'0',"
            . "'" . $stil . "',"
            . "'" . json_encode($shops_ids, JSON_UNESCAPED_UNICODE) . "'"
            . ")");
}

function json_decode_nice($json) {
    $json = str_replace("\n", "\\n", $json);
    $json = str_replace("\r", "", $json);
    $json = preg_replace('/([{,]+)(\s*)([^"]+?)\s*:/', '$1"$3":', $json);
    $json = preg_replace('/(,)\s*}$/', '}', $json);
    return json_decode($json);
}
