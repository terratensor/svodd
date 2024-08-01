# Как пользоваться поиском

# Полнотекстовые операторы

Строка запроса может содержать конкретные операторы, которые определяют условия для сопоставления слов из строки запроса.

### Логические операторы

#### Оператор AND

Всегда присутствует неявный логический оператор AND, поэтому "hello world" подразумевает, что в соответствующем документе должны быть найдены как "hello", так и "world".

```sql
hello  world
```

Примечание: Здесь нет явного оператора "И".

#### Оператор OR

Логический оператор OR `|` имеет более высокий приоритет, чем AND, поэтому "поиск кошки | собаки | мыши" означает "поиск (кошки | собаки | мыши)", а не "(поиск кошки) | собаки | мыши".

```sql
hello | world
```

Примечание: Здесь нет оператора `ИЛИ`. Пожалуйста, используйте вместо него `|`.

### Оператор MAYBE

```sql
hello MAYBE world
```

Оператор `MAYBE` работает аналогично оператору `|`, но он не возвращает документы, которые соответствуют только правильному выражению поддерева.

### Оператор отрицания

```sql
hello -world
hello !world
```

Оператор отрицания применяет правило, согласно которому слово не существует.

Запросы, содержащие **только** отрицания, по умолчанию не поддерживаются. Чтобы включить, используйте параметр сервера [not_terms_only_allowed](../../Server_settings/Searchd.md#not_terms_only_allowed).

### Оператор поиска по полю

```sql
@title hello @body world
```

Оператор ограничения поля ограничивает последующие поиски указанным полем. По умолчанию запрос завершается ошибкой с сообщением об ошибке, если заданное имя поля не существует в искомой таблице. Однако это поведение можно предотвратить, указав параметр "@@relaxed" в начале запроса:

```sql
@@relaxed @nosuchfield my query
```

Это может быть полезно при поиске в разнородных таблицах с различными схемами.

Ограничения на расположение полей дополнительно ограничивают поиск до первых N позиций в пределах данного поля (или полей). Например, `@body [50] hello` не будет соответствовать документам, в которых ключевое слово `hello` появляется в позиции 51 или позже в тексте.

```sql
@body[50] hello
```

Оператор многопольного поиска:

```sql
@(title,body) hello world
```

Игнорировать оператор поиска по полю (игнорирует любые совпадения с "hello world" из поля "название"):

```sql
@!title hello world
```

Игнорируйте оператор поиска по нескольким полям — если есть поля 'title', 'subject' и 'body', то `@!(title)` эквивалентно `@(subject, body)`:

```sql
@!(title,body) hello world
```

Оператор поиска по всем полям:

```sql
@* hello
```

### Оператор поиска по фразе

```sql
"hello world"
```

Оператор фразы требует, чтобы слова располагались рядом друг с другом.

Оператор поиска по фразе может включать модификатор `match any term`. В операторе "фраза" термины являются позиционно значимыми. При использовании модификатора "соответствовать любому термину" позиции последующих терминов в запросе по фразе будут смещены. В результате модификатор "соответствует любому" не влияет на производительность поиска.

```sql
"exact * phrase * * for terms"
```

### Оператор близкого поиска

```sql
"hello world"~10
```

Расстояние близости измеряется в словах с учетом количества слов и применяется ко всем словам, заключенным в кавычки. Например, запрос `"кошка, собака, мышь"~5` указывает, что в нем должно быть не более 8 слов, содержащих все 3 слова. Следовательно, документ с `КОШКА aaa bbb ccc СОБАКА eee fff МЫШЬ` не будет соответствовать этому запросу, так как его длина составляет ровно 8 слов.

### Оператор сопоставления кворума

```sql
"the world is a wonderful place"/3
```

Оператор сопоставления кворума вводит тип нечеткого сопоставления. Он будет сопоставлять только те документы, которые соответствуют заданному пороговому значению заданных слов. В приведенном выше примере `"the world is a wonderful place"/3` он будет соответствовать всем документам, содержащим как минимум 3 из 6 указанных слов. Оператор ограничен 255 ключевыми словами. Вместо абсолютного числа вы также можете указать значение от 0,0 до 1,0 (что соответствует 0% и 100%), и система будет сопоставлять только документы, содержащие по крайней мере указанный процент заданных слов. Тот же пример, что был приведен выше, можно также выразить как `"the world is a wonderful place"/0.5`, и он будет соответствовать документам, содержащим не менее 50% из 6 слов.

### Оператор строгого порядка

```sql
aaa << bbb << ccc
```

Оператор строгого порядка (также известный как оператор "before") соответствует документу только в том случае, если его ключевые слова-аргументы отображаются в документе точно в том порядке, который указан в запросе. Например, запрос "черный << кот" будет соответствовать документу "черно-белый кот", но не документу "этот кот был черным". Оператор порядка имеет самый низкий приоритет. Его можно применять как к отдельным ключевым словам, так и к более сложным выражениям. Например, это корректный запрос:

```sql
(набор слов) << "точная фраза" << красный|зеленый|синий
```

### Точный модификатор формы

```sql
raining =cats and =dogs
="exact phrase"
```

Модификатор ключевого слова "Точная форма" соответствует документу только в том случае, если ключевое слово отображается в точной указанной форме. По умолчанию документ считается соответствующим, если ключевое слово с основой/лемматизацией совпадает. Например, запрос "бегун" будет соответствовать как документу, содержащему "бегал", так и документу, содержащему "бегущий", поскольку обе формы содержат просто "бег". Однако запрос "=бегун" будет соответствовать только первому документу. Для использования оператора точной формы требуется чтобы в системе был включен параметр [index_exact_words](../../Creating_a_table/NLP_and_tokenization/Morphology.md#index_exact_words).

<!-- Another use case is to prevent [expanding](../../Creating_a_table/NLP_and_tokenization/Wildcard_searching_settings.md#expand_keywords) a keyword to its `*keyword*` form. For example, with `index_exact_words=1` + `expand_keywords=1/star`, `bcd` will find a document containing `abcde`, but `=bcd` will not.

As a modifier affecting the keyword, it can be used within operators such as phrase, proximity, and quorum operators. Applying an exact form modifier to the phrase operator is possible, and in this case, it internally adds the exact form modifier to all terms in the phrase.

### Wildcard operators

```sql
nation* *nation* *national
```

Requires [min_infix_len](../../Creating_a_table/NLP_and_tokenization/Wildcard_searching_settings.md#min_infix_len) for prefix (expansion in trail) and/or suffix (expansion in head). If only prefixing is desired, [min_prefix_len](../../Creating_a_table/NLP_and_tokenization/Wildcard_searching_settings.md#min_prefix_len) can be used instead.

The search will attempt to find all expansions of the wildcarded tokens, and each expansion is recorded as a matched hit. The number of expansions for a token can be controlled with the [expansion_limit](../../Creating_a_table/NLP_and_tokenization/Wildcard_searching_settings.md#expansion_limit) table setting. Wildcarded tokens can have a significant impact on query search time, especially when tokens have short lengths. In such cases, it is desirable to use the expansion limit.

The wildcard operator can be automatically applied if the [expand_keywords](../../Searching/Options.md#expand_keywords) table setting is used.

In addition, the following inline wildcard operators are supported:

* `?` can match any single character: `t?st` will match `test`, but not `teast`
* `%` can match zero or one character: `tes%` will match `tes` or `test`, but not `testing`

The inline operators require `dict=keywords` and infixing enabled.

### REGEX operator

```sql
REGEX(/t.?e/)
```

Requires the [min_infix_len](../../Creating_a_table/NLP_and_tokenization/Wildcard_searching_settings.md#min_infix_len) or [min_prefix_len](../../Creating_a_table/NLP_and_tokenization/Wildcard_searching_settings.md#min_prefix_len) and [dict](../../Creating_a_table/NLP_and_tokenization/Low-level_tokenization.md#dict)=keywords options to be set (which is a default).

Similarly to the [wildcard operators](../../Searching/Full_text_matching/Operators.md#Wildcard-operators), the REGEX operator attempts to find all tokens matching the provided pattern, and each expansion is recorded as a matched hit. Note, this can have a significant impact on query search time, as the entire dictionary is scanned, and every term in the dictionary undergoes matching with the REGEX pattern.

The patterns should adhere to the [RE2 syntax](https://github.com/google/re2/wiki/Syntax). The REGEX expression delimiter is the first symbol after the open bracket. In other words, all text between the open bracket followed by the delimiter and the delimiter and the closed bracket is considered as a RE2 expression.
Please note that the terms stored in the dictionary undergo `charset_table` transformation, meaning that for example, REGEX may not be able to match uppercase characters if all characters are lowercased according to the `charset_table` (which happens by default). To successfully match a term using a REGEX expression, the pattern must correspond to the entire token. To achieve partial matching, place `.*` at the beginning and/or end of your pattern.

```sql
REGEX(/.{3}t/)
REGEX(/t.*\d*/)
```

### Field-start and field-end modifier

```sql
^hello world$
```

Field-start and field-end keyword modifiers ensure that a keyword only matches if it appears at the very beginning or the very end of a full-text field, respectively. For example, the query `"^hello world$"` (enclosed in quotes to combine the phrase operator with the start/end modifiers) will exclusively match documents containing at least one field with these two specific keywords.

### IDF boost modifier

```sql
boosted^1.234 boostedfieldend$^1.234
```

The boost modifier raises the word [IDF](../../Searching/Options.md#idf)_score by the indicated factor in ranking scores that incorporate IDF into their calculations. It does not impact the matching process in any manner.

### NEAR operator

```sql
hello NEAR/3 world NEAR/4 "my test"
```

The `NEAR` operator is a more generalized version of the proximity operator. Its syntax is `NEAR/N`, which is case-sensitive and does not allow spaces between the `NEAR` keywords, slash sign, and distance value.

While the original proximity operator works only on sets of keywords, `NEAR` is more versatile and can accept arbitrary subexpressions as its two arguments. It matches a document when both subexpressions are found within N words of each other, regardless of their order. `NEAR` is left-associative and shares the same (lowest) precedence as [BEFORE](../../Searching/Full_text_matching/Operators.md#Strict-order-operator).

It is important to note that `one NEAR/7 two NEAR/7 three` is not exactly equivalent to `"one two three"~7`. The key difference is that the proximity operator allows up to 6 non-matching words between all three matching words, while the version with `NEAR` is less restrictive: it permits up to 6 words between `one` and `two`, and then up to 6 more between that two-word match and `three`.

### NOTNEAR operator

```sql
Church NOTNEAR/3 street
```
The `NOTNEAR` operator serves as a negative assertion. It matches a document when the left argument is present and either the right argument is absent from the document or the right argument is a specified distance away from the end of the left matched argument. The distance is denoted in words. The syntax is `NOTNEAR/N`, which is case-sensitive and does not permit spaces between the `NOTNEAR` keyword, slash sign, and distance value. Both arguments of this operator can be terms or any operators or group of operators.

### SENTENCE and PARAGRAPH operators

```sql
all SENTENCE words SENTENCE "in one sentence"
```


```sql
"Bill Gates" PARAGRAPH "Steve Jobs"
```
The `SENTENCE` and `PARAGRAPH` operators match a document when both of their arguments are within the same sentence or the same paragraph of text, respectively. These arguments can be keywords, phrases, or instances of the same operator.

The order of the arguments within the sentence or paragraph is irrelevant. These operators function only with tables built with [index_sp](../../Creating_a_table/NLP_and_tokenization/Advanced_HTML_tokenization.md#index_sp) (sentence and paragraph indexing feature) enabled and revert to a simple AND operation otherwise. For information on what constitutes a sentence and a paragraph, refer to the [index_sp](../../Creating_a_table/NLP_and_tokenization/Advanced_HTML_tokenization.md#index_sp) directive documentation.


### ZONE limit operator

```sql
ZONE:(h3,h4)

only in these titles
```

The `ZONE limit` operator closely resembles the field limit operator but limits matching to a specified in-field zone or a list of zones. It is important to note that subsequent subexpressions do not need to match within a single continuous span of a given zone and may match across multiple spans. For example, the query `(ZONE:th hello world)` will match the following sample document:

```html
<th>Table 1. Local awareness of Hello Kitty brand.</th>
.. some table data goes here ..
<th>Table 2. World-wide brand awareness.</th>
```

The `ZONE` operator influences the query until the next field or `ZONE` limit operator, or until the closing parenthesis. It functions exclusively with tables built with zone support (refer to [index_zones](../../Creating_a_table/NLP_and_tokenization/Advanced_HTML_tokenization.md#index_zones)) and will be disregarded otherwise.

### ZONESPAN limit operator

```sql
ZONESPAN:(h2)

only in a (single) title
```

The `ZONESPAN` limit operator resembles the `ZONE` operator but mandates that the match occurs within a single continuous span. In the example provided earlier, `ZONESPAN:th hello world` would not match the document, as "hello" and "world" do not appear within the same span.

<!-- proofread -->
