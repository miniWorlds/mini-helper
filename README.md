# 常用的一些扩展类库

> 更新完善中

> 以下类库都在`\\mini\\helper`命名空间下

## Str
> 字符串操作

```
// 检查字符串中是否包含某些字符串
Str::contains($haystack, $needles)

// 检查字符串是否以某些字符串结尾
Str::endsWith($haystack, $needles)

// 获取指定长度的随机字母数字组合的字符串
Str::random($length = 16)

// 字符串转小写
Str::lower($value)

// 字符串转大写
Str::upper($value)

// 获取字符串的长度
Str::length($value)

// 截取字符串
Str::substr($string, $start, $length = null)

```

## Time
> 时间戳操作

```
// 今日开始和结束的时间戳
Time::today();

// 明日开始和结束得时间戳
Time::tomorrow();

// 昨日开始和结束的时间戳
Time::yesterday();

// 本周开始和结束的时间戳
Time::week();

// 上周开始和结束的时间戳
Time::lastWeek();

// 本月开始和结束的时间戳
Time::month();

// 上月开始和结束的时间戳
Time::lastMonth();

// 今年开始和结束的时间戳
Time::year();

// 去年开始和结束的时间戳
Time::lastYear();

// 获取7天前零点到现在的时间戳
Time::dayToNow(7)

// 获取7天前零点到昨日结束的时间戳
Time::dayToNow(7, true)

// 获取7天前的时间戳
Time::daysAgo(7)

// 获取7天后的时间戳
Time::daysAfter(7)

// 天数转换成秒数
Time::daysToSecond(5)

// 周数转换成秒数
Time::weekToSecond(5)

// 当前毫秒时间戳
Time::millisecond()

// 毫秒时间戳转日期时间
Time::millisecondToDate(1639972562000)

```